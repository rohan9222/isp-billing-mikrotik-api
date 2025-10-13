<?php

namespace App\Imports;

// for excel data import

use App\Models\PaymentSummary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MonthlyBillingImport implements ToModel, WithHeadingRow
{
    use Importable;

    public $uploadedRows = 0;

    public $skippedRows = 0;

    public $duplicates = [];

    public function model(array $row)
    {
        // Check if the customer already exists by unique ID (SSN)
        // $duplicateData = PaymentSummary::where('customer_unique_id', $row['id'])->exists();
        // if ($duplicateData) {
        //     $this->skippedRows++;
        //     $this->duplicates[] = $row;
        //     return null;
        // }
        // dd($row);
        // Fill empty fields with default value '-'
        // $row = array_map(fn($value) => $value ?: '-', $row);

        DB::beginTransaction();
        try {
            // Create billing information
            PaymentSummary::create([
                'customer_payment_unique_id' => $row['user'],
                'summary_date' => $this->convertExcelDate($row['date']),
                'monthly_rent' => $this->normalizeValue($row['monthly_rent']),
                'additional_charge' => $this->normalizeValue($row['add_charge']),
                'previous_due' => $this->normalizeValue($row['previous_due']),
                'discount' => $this->normalizeValue($row['discount']),
                'advance' => $this->normalizeValue($row['advance']),
                'vat' => $this->normalizeValue($row['vat']),
            ]);

            // $this->uploadedRows++;
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
