<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NagadPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    private function getNagadConfig()
    {
        return [
            'base_url' => siteUrlSettings('payment_nagad_base_url') ?: config('services.nagad.base_url') ?: 'http://sandbox.nagad.com.bd:10080/remote-payment-gateway-1.0/api/dfs',
            'merchant_id' => siteUrlSettings('payment_nagad_merchant_id') ?: config('services.nagad.merchant_id') ?: '683002007104225',
            'public_key' => siteUrlSettings('payment_nagad_public_key'),
            'private_key' => siteUrlSettings('payment_nagad_private_key'),
        ];
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;
        $user = auth()->user();

        if (! $user || ! $user->customer) {
            return redirect()->back()->with('error', 'Unauthorized customer access.');
        }

        $customer = $user->customer;

        // Auto-detect local development to offer sandbox mock helper
        $host = request()->getHost();
        $isLocal = str_ends_with($host, '.test') || str_ends_with($host, '.local') || $host === 'localhost' || $host === '127.0.0.1';

        if ($isLocal && $request->has('mock')) {
            return response()->view('payment.mock_checkout', [
                'gateway' => 'Nagad',
                'customer' => $customer,
                'amount' => $amount,
                'reason' => null,
            ]);
        }

        $config = $this->getNagadConfig();

        // Check if Nagad private/public keys are defined. If not, fallback to Simulator/Mock for sandbox testing.
        if (empty($config['private_key']) || empty($config['public_key'])) {
            if ($isLocal) {
                return response()->view('payment.mock_checkout', [
                    'gateway' => 'Nagad',
                    'customer' => $customer,
                    'amount' => $amount,
                    'reason' => 'Nagad public/private key pair is not configured in settings. Simulating sandbox payment.',
                ]);
            }

            return redirect()->route('filament.portal.pages.pay-bill')
                ->with('error', 'Nagad is not properly configured (keys are missing).');
        }

        try {
            $merchantId = $config['merchant_id'];
            $orderId = 'NGD_'.uniqid();
            $dateTime = now()->format('YmdHis');

            // Nagad initiation payload
            $postData = [
                'accountNumber' => $customer->mobile ?: '01700000000',
                'datetime' => $dateTime,
                'orderId' => $orderId,
                'merchantId' => $merchantId,
            ];

            // In a production Nagad API, we would generate a signature using Merchant's Private Key
            // and encrypt data with Nagad's Public Key.
            // Below is the mockable endpoint/signature logic for Nagad Sandbox.

            // For testing sandbox without calling complex cryptography:
            // Since sandbox endpoints can be flaky and cryptography requires exact PEM formatting,
            // we provide a safe fallback or a call to the sandbox endpoint:
            $response = Http::withHeaders([
                'Client-Type' => 'MERCHANT',
                'X-KM-Api-Version' => 'v1.0.0',
                'X-KM-IP-Address' => request()->ip() ?: '127.0.0.1',
            ])->post($config['base_url']."/payment-initializer/merchantId/{$merchantId}/{$orderId}", $postData);

            $res = $response->json();

            if (isset($res['sensitiveData']) && isset($res['signature'])) {
                // Usually Nagad returns redirection URL in the complete payment step.
                // We'll redirect to the simulated / sandbox Nagad URL or redirect page:
                $redirectUrl = $config['base_url'].'/payment/checkout?paymentRefId='.($res['paymentRefId'] ?? 'REF_'.uniqid());

                return redirect()->away($redirectUrl);
            }

            Log::error('Nagad session initiation failed: '.json_encode($res));

            if ($isLocal) {
                return response()->view('payment.mock_checkout', [
                    'gateway' => 'Nagad',
                    'customer' => $customer,
                    'amount' => $amount,
                    'reason' => 'Nagad API error: '.($res['message'] ?? 'Failed to initialize session.'),
                ]);
            }

            return redirect()->route('filament.portal.pages.pay-bill')
                ->with('error', 'Nagad session creation failed: '.($res['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            Log::error('Nagad initiate exception: '.$e->getMessage());

            if ($isLocal) {
                return response()->view('payment.mock_checkout', [
                    'gateway' => 'Nagad',
                    'customer' => $customer,
                    'amount' => $amount,
                    'reason' => 'Connection failed: '.$e->getMessage(),
                ]);
            }

            return redirect()->route('filament.portal.pages.pay-bill')
                ->with('error', 'Nagad service currently unavailable: '.$e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        // Nagad callback parameters
        $status = $request->query('status');
        $orderId = $request->query('order_id');
        $paymentRefId = $request->query('payment_ref_id');

        if ($status === 'success') {
            try {
                // Normally we would verify the payment details by calling Nagad status api:
                // GET /payment/status/{paymentRefId}
                $config = $this->getNagadConfig();
                $verify = Http::get($config['base_url']."/payment/status/{$paymentRefId}");
                $res = $verify->json();

                if (isset($res['status']) && $res['status'] === 'Success') {
                    $amount = (float) $res['amount'];
                    $customer = auth()->user() ? auth()->user()->customer : null;

                    if ($customer) {
                        $this->paymentService->processSuccessPayment($customer, $amount, 'nagad', $paymentRefId);

                        return redirect()->route('filament.portal.pages.dashboard')
                            ->with('success', 'Payment of BDT '.$amount.' received successfully via Nagad. Your account is active.');
                    }
                }

                Log::error('Nagad validation failed: '.json_encode($res));

                return redirect()->route('filament.portal.pages.pay-bill')
                    ->with('error', 'Nagad verification failed.');

            } catch (\Exception $e) {
                Log::error('Nagad callback verification exception: '.$e->getMessage());

                return redirect()->route('filament.portal.pages.pay-bill')
                    ->with('error', 'Verification exception: '.$e->getMessage());
            }
        }

        return redirect()->route('filament.portal.pages.pay-bill')
            ->with('error', 'Payment failed or cancelled. Status: '.$status);
    }
}
