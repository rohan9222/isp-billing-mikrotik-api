<?php

namespace App\Services;

use App\Http\Controllers\MikrotikController;
use App\Http\Controllers\SMSController;
use App\Models\BillingInfo;
use App\Models\CollectionSummary;
use App\Models\CustomersInfo;
use App\Models\PaymentSummary;
use App\Models\PPPSecrets;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process a successful online payment, update billing records,
     * record collection summary, and activate the user on MikroTik.
     */
    public function processSuccessPayment(CustomersInfo $customer, float $amount, string $gateway, string $trxId): bool
    {
        DB::beginTransaction();
        try {
            $billing = $customer->billing;
            if (! $billing) {
                throw new \Exception("Billing information not found for customer {$customer->customer_unique_id}");
            }

            // 1. Calculate due and expire date
            $currentDue = (float) $billing->due_amount;
            $newDue = max(0.00, $currentDue - $amount);
            $advancePaid = max(0.00, $amount - $currentDue);

            $rent = (float) $billing->monthly_rent ?: 1.00;
            $expireDate = $billing->auto_disable_date;

            if ($newDue > 0) {
                $extra_month = floor($newDue / $rent);
                if ($extra_month < $billing->auto_disable_month) {
                    $expireDate = Carbon::parse($billing->auto_disable_date)
                        ->month(now()->month)->year(now()->year)
                        ->subMonths($extra_month)
                        ->format('Y-m-d');
                } else {
                    $expireDate = Carbon::parse($billing->auto_disable_date)
                        ->month(now()->month)->year(now()->year)
                        ->subMonths($billing->auto_disable_month)
                        ->addMonths(1)
                        ->format('Y-m-d');
                }
            } elseif ($advancePaid > 0) {
                $extra_month = floor($advancePaid / $rent);
                $expireDate = Carbon::parse($billing->auto_disable_date)
                    ->month(now()->month)->year(now()->year)
                    ->addMonths($extra_month)
                    ->format('Y-m-d');
            } else {
                $expireDate = Carbon::parse($billing->auto_disable_date)
                    ->month(now()->month)->year(now()->year)
                    ->addMonths(1)
                    ->format('Y-m-d');
            }

            // Generate next sequential invoice number
            $maxInvoice = CollectionSummary::max('invoice_no');
            $invoiceNo = $maxInvoice ? ($maxInvoice + 1) : 100001;

            // 2. Create Collection Record
            CollectionSummary::create([
                'customer_collection_unique_id' => $customer->customer_unique_id,
                'collection_date' => Carbon::now(),
                'collection_amount' => $amount,
                'collected_by' => 'Online Payment ('.strtoupper($gateway).')',
                'payment_type' => 'online',
                'payment_method' => $gateway,
                'transaction_id' => $trxId,
                'payment_status' => 'paid',
                'invoice_no' => $invoiceNo,
                'bill_month' => Carbon::now()->format('F Y'),
            ]);

            // 3. Update BillingInfo
            $billing->update([
                'paid_amount' => $billing->paid_amount + $amount,
                'paid_date' => Carbon::now(),
                'auto_disable_date' => $expireDate,
                'due_amount' => $newDue,
            ]);

            // 4. Create Monthly Payment Summary if not exists for the current month
            $summaryExists = PaymentSummary::where('customer_payment_unique_id', $customer->customer_unique_id)
                ->where('summary_date', Carbon::now()->firstOfMonth()->format('Y-m-d'))
                ->exists();

            if (! $summaryExists) {
                PaymentSummary::create([
                    'customer_payment_unique_id' => $customer->customer_unique_id,
                    'summary_date' => Carbon::now()->firstOfMonth()->format('Y-m-d'),
                    'monthly_rent' => $billing->monthly_rent,
                    'additional_charge' => $billing->additional_charge,
                    'vat' => $billing->vat,
                    'previous_due' => $billing->previous_due,
                    'advance' => $billing->advance,
                    'discount' => $billing->discount,
                ]);
            }

            // 5. Update customer status to active and reset disable count
            $customer->status = 'active';
            $customer->disable_count = 0;
            $customer->save();

            // 6. Extend auto_disable_date if today is beyond it
            if ($billing->auto_disable_date) {
                $autoDisableDate = Carbon::parse($billing->auto_disable_date)->startOfDay();
                $autoDisableMonth = $billing->auto_disable_month;
                $disableDate = $autoDisableDate->copy()->addMonths($autoDisableMonth);

                if ($disableDate->lte(today())) {
                    while ($disableDate->lte(today())) {
                        $disableDate->addMonth();
                    }

                    $billing->auto_disable_date = $disableDate->copy()->subMonths($autoDisableMonth)->toDateString();
                    $billing->save();
                }
            }

            // 7. Enable customer on Mikrotik Router
            if ($customer->pppUser) {
                // Enable secret (calls router via API/SSH)
                app(MikrotikController::class)->enablePPPSecret(
                    $customer->customer_unique_id,
                    $customer->pppUser->router_name,
                    $customer->pppUser->username
                );

                // Restore their original plan profile
                app(MikrotikController::class)->updatePPPSecret(
                    $customer->pppUser->router_name,
                    $customer->pppUser->username,
                    'profile',
                    $customer->pppUser->profile
                );

                // Kick any current session to force reconnect with the active profile
                try {
                    app(MikrotikController::class)->singleWrite(
                        $customer->pppUser->router_name,
                        '/ppp active remove [find name="'.$customer->pppUser->username.'"]'
                    );
                } catch (\Exception $e) {
                    Log::debug('PaymentService session kick skipped: '.$e->getMessage());
                }

                // Sync the local PPPSecrets status
                PPPSecrets::where('id', $customer->ppp_user_id)->update(['status' => 'active']);
            }

            DB::commit();

            // 8. Send SMS confirmation
            try {
                $smsData = [
                    'recipient' => $customer->mobile,
                    'customer_name' => $customer->customer_name,
                    'collection_amount' => $amount,
                    'ip_or_user_name' => $customer->pppUser->username ?? '',
                    'due_amount' => $newDue,
                    'company_name' => siteUrlSettings('site_name') ?: config('app.name'),
                ];
                app(SMSController::class)->paymentCollectionSMS($smsData);
            } catch (\Exception $smsEx) {
                Log::error("SMS Confirmation failed for {$customer->customer_unique_id}: ".$smsEx->getMessage());
            }

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PaymentService processing failed: '.$e->getMessage());
            throw $e;
        }
    }
}
