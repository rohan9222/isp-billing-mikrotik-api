<?php

namespace App\Imports;

// for excel data import

use App\Models\BillingInfo;
use App\Models\CustomersAddress;
use App\Models\CustomersInfo;
use App\Models\OfficialInfo;
use App\Models\PPPSecrets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $uploadedRows = 0;

    public $skippedRows = 0;

    public $duplicates = [];

    public function model(array $row)
    {
        // Check if the customer already exists by unique ID (SSN)
        // $duplicateData = CustomersInfo::where('customer_unique_id', $row['id'])->exists();
        // if ($duplicateData) {
        //     $this->skippedRows++;
        //     $this->duplicates[] = $row;
        //     return null;
        // }

        // Fill empty fields with default value '-'
        $row = array_map(fn ($value) => $value ?: '-', $row);

        DB::beginTransaction();
        try {
            // Check for PPPSecrets, if user exists
            // \dd($row);
            $ppe = $row['username'] ? PPPSecrets::where('username', $row['username'])->first() : null;
            // dd($ppe,$row['username'],$row['id']);
            // Check for connected user by name
            $user = $row['connected_by'] ? User::where('name', $row['connected_by'])->first() : null;
            // dd($this->convertExcelDate($row['entry_date']));
            // Create customer information
            $customer = CustomersInfo::where('customer_unique_id', $row['id'])->update([
                'ppp_user_id' => $ppe->id ?? null,
            ]);
            // $customer = CustomersInfo::create([
            //     'customer_unique_id' => $row['id'],
            //     'customer_name' => $row['name'],
            //     // 'address' => $row['address'],
            //     'mobile' => $row['mobile'],
            //     'ppp_user_id' => $ppe->id ?? null,
            //     'status' => $row['status'],
            //     'contact_person' => $row['contact_person'],
            //     'identification_no' => $row['identification_no'],
            //     'package_name' => ($row['package'] === '-' || $row['package'] === '') ? null : $row['package'],
            //     'connection_date' => ($row['entry_date'] === '-' || $row['entry_date'] === '') ? null : $this->convertExcelDate($row['entry_date']),
            // ]);

            // Create address information
            //             CustomersAddress::create([
            //                 'customer_address_unique_id' => $customer->customer_unique_id,
            //                 'label_name' =>'District',
            //                 'input_type_dropdown' => $row['district'],
            //             ]);
            //             CustomersAddress::create([
            //                 'customer_address_unique_id' => $customer->customer_unique_id,
            //                 'label_name' =>'Thana',
            //                 'input_type_dropdown' => $row['thana'],
            //             ]);
            //             CustomersAddress::create([
            //                 'customer_address_unique_id' => $customer->customer_unique_id,
            //                 'label_name' =>'Area',
            //                 'input_type_dropdown' => $row['area'],
            //             ]);

            //             OfficialInfo::create([
            //                 'customer_office_unique_id' => $customer->customer_unique_id, // Ensure this is not null
            //                 'client_type' => $row['client_type'],
            //                 'connected_by' => $user ? $user->id : null,
            //                 'billing_type' => 'prepaid',
            //             ]);
            // // dd(Carbon::createFromFormat('d-M-Y', $row['expire_date'])->format('Y-m-d'));
            //             // Create billing information
            //             BillingInfo::create([
            //                 'customer_bill_unique_id' => $customer->customer_unique_id,
            //                 'monthly_rent' => $this->normalizeValue($row['monthly_rent']),
            //                 'previous_due' => $this->normalizeValue($row['previous_due']),
            //                 'discount' => $this->normalizeValue($row['discount']),
            //                 'advance' => $this->normalizeValue($row['advance']),
            //                 'additional_charge' => $this->normalizeValue($row['add_charge']),
            //                 'vat' => $this->normalizeValue($row['vat']),
            //                 'total_amount' => $this->normalizeValue($row['total_amount']),
            //                 'paid_amount' => $this->normalizeValue($row['collection_amount']),
            //                 'due_amount' => $this->normalizeValue($row['total_due']),
            //                 'auto_disable_date' => ($row['expire_date'] === '-' || $row['expire_date'] === '') ? null : $this->convertExcelDate($row['expire_date']),
            //                 'auto_disable_month' => ($row['expire_month'] === '-') ? 0 : $row['expire_month'],
            //             ]);

            $this->uploadedRows++;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import error: '.$e->getMessage(), ['row' => $row]);
            $this->skippedRows++;
            $this->duplicates[] = $row;

            return null;
        }

    }

    // Normalize value to avoid empty or null fields
    private function normalizeValue($value)
    {
        return $value === '-' ? 0 : $value;
    }

    private function convertExcelDate($excelDate)
    {
        // Check if the value is numeric (Excel serial date)
        if (is_numeric($excelDate)) {
            return Carbon::instance(Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
        }

        // Return null if the date is not valid or numeric
        return null;
    }
}
