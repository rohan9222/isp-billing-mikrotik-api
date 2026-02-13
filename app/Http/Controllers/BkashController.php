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
        $response = Http::withBasicAuth(config('services.bkash.username'), config('services.bkash.password'))
            ->post(config('services.bkash.base_url').'/tokenized/checkout/token/grant', [
                'app_key' => config('services.bkash.app_key'),
                'app_secret' => config('services.bkash.app_secret'),
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
            ->post(config('services.bkash.base_url').'/tokenized/checkout/create', [
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
            ->post(config('services.bkash.base_url').'/tokenized/checkout/execute', [
                'paymentID' => $paymentID,
            ]);

        return $execution->json();
    }
}
