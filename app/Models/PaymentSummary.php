<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSummary extends Model
{
    protected $fillable = [
        'customer_payment_unique_id',
        // 'ppp_username',
        'summary_date',
        'monthly_rent',
        'additional_charge',
        'discount',
        'advance',
        'vat',
        'previous_due',
    ];

    // protected static function booted(){
    //     static::created(function ($product) {
    //         BillingInfo::create([
    //             'customer_payment_unique_id' => $product->customer_bill_unique_id,
    //             'ppp_username' => CustomersInfo::where('customer_unique_id', $product->customer_bill_unique_id)->first()->ppp_username,
    //             'monthly_rent' => $product->monthly_rent,
    //             'additional_charge' => $product->additional_charge,
    //             'discount' => $product->discount,
    //             'advance' => $product->advance,
    //             'vat' => $product->vat,
    //             'previous_due' => $product->previous_due,
    //             'due_amount' => $product->due_amount,
    //             'total_due_amount' => $product->total_due_amount,
    //             'paid_amount' => $product->paid_amount,
    //             'total_amount' => $product->total_amount,
    //             'payment_date' => $product->paid_date,
    //             'collected_by' => strtok(auth()->user()->email, '@'),
    //         ]);
    //     });
    // }
}
