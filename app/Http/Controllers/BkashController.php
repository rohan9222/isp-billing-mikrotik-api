<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BkashController extends Controller
{
    public function index()
    {
        return view('bkash_payment');
    }

    // Token Generation ফাংশন
    public function generateToken()
    {
        $response = Http::withBasicAuth(env('BKASH_USERNAME'), env('BKASH_PASSWORD'))
            ->post(env('BKASH_BASE_URL').'/tokenized/checkout/token/grant', [
                'app_key' => env('BKASH_APP_KEY'),
                'app_secret' => env('BKASH_APP_SECRET'),
            ]);

        $responseBody = $response->json();

        if (! isset($responseBody['id_token'])) {
            return response()->json(['error' => 'Token generation failed'], 500);
        }

        return $responseBody;
    }

    // পেমেন্ট রিকুয়েস্ট ফাংশন
    public function createPayment(Request $request)
    {
        $token = $this->generateToken(); // টোকেন জেনারেট

        $payment = Http::withToken($token['id_token'])
            ->post(env('BKASH_BASE_URL').'/tokenized/checkout/create', [
                'amount' => $request->amount,
                'merchantInvoiceNumber' => uniqid(),
                'payerReference' => 'YourReference',
                'currency' => 'BDT',
                'intent' => 'sale',
            ]);

        return $payment->json();
    }

    // পেমেন্ট এক্সিকিউশন ফাংশন
    public function executePayment($paymentID)
    {
        $token = $this->generateToken();

        $execution = Http::withToken($token['id_token'])
            ->post(env('BKASH_BASE_URL').'/tokenized/checkout/execute', [
                'paymentID' => $paymentID,
            ]);

        return $execution->json();
    }
}
