<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use App\Services\SMSService;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function allCustomersSMS(array $data)
    {
        $template = SmsTemplate::where('template_name', 'all_customers')->first();

        if (! $template) {
            return response()->json(['status' => 'error', 'message' => 'Template not found'], 404);
        }

        if ($template->is_active == 1) {
            $message = str_replace(
                ['{CUSTOMER_NAME}', '{MONTH}', '{BILL_AMOUNT}', '{CUSTOMER_ID}', '{IP_OR_USER_NAME_OR_ID}', '{LAST_DAY_OF_PAY_BILL}', '{COMPANY_NAME}', '{COMPANY_MOBILE}'],
                [$data['customer_name'], $data['month'], $data['bill_amount'], $data['customer_id'], $data['ip_or_user_name'], $data['last_day_of_pay_bill'], $data['company_name'], $data['company_mobile']],
                $template->template
            );
            // dd($message);

            $response = $this->smsService->sendSMS($data['recipient'], $message);

            return $response;
        }
    }

    public function paymentCollectionSMS(array $data)
    {
        // Fetch the message template from the database
        $template = SmsTemplate::where('template_name', 'payment_collection')->first();

        if (! $template) {
            return response()->json(['status' => 'error', 'message' => 'Template not found'], 404);
        }

        // Determine if due or advance
        $balanceText = $data['due_amount'] < 0
            ? 'Advance: '.abs($data['due_amount'])
            : 'Due: '.$data['due_amount'];

        // Replace placeholders with actual values
        $message = str_replace(
            ['{CUSTOMER_NAME}', '{AMOUNT}', '{IP_OR_USER_NAME_OR_ID}', '{BALANCE}', '{COMPANY_NAME}'],
            [$data['customer_name'], $data['collection_amount'], $data['ip_or_user_name'], $balanceText, $data['company_name']],
            $template->template
        );

        // Send the SMS
        $response = $this->smsService->sendSMS($data['recipient'], $message);

        return $response;
    }

    public function collectionDeleteSMS(array $data)
    {
        // Fetch the message template from the database
        $template = SmsTemplate::where('template_name', 'collection_delete')->first();

        if (! $template) {
            return response()->json(['status' => 'error', 'message' => 'Template not found'], 404);
        }

        // Determine if due or advance
        $balanceText = $data['due_amount'] < 0
            ? 'Advance: '.abs($data['due_amount']).'/='
            : 'Due: '.$data['due_amount'].'/=';

        // Replace placeholders with actual values
        $message = str_replace(
            ['{CUSTOMER_NAME}', '{AMOUNT}', '{IP_OR_USER_NAME_OR_ID}', '{TOTAL_COLLECTION}', '{COMPANY_MOBILE}'],
            [$data['customer_name'], $data['collection_amount'], $data['ip_or_user_name'], $balanceText, $data['company_mobile']],
            $template->template
        );

        // Send the SMS
        $response = $this->smsService->sendSMS($data['recipient'], $message);

        return $response;
    }

    // use this template for only input form

    // public function sendInputSMS(Request $request)
    // {
    //     $validated = $request->validate([
    //         'recipient'      => 'required|string',
    //         'customer_name'  => 'required|string',
    //         'amount'         => 'required|numeric',
    //         'ip_or_user_name'=> 'required|string',
    //         'due_amount'     => 'required|numeric',
    //         'company_name'   => 'required|string',
    //     ]);

    //     // Fetch the template from the database
    //     $template = SmsTemplate::where('template_name', 'payment_due')->first();

    //     if (!$template) {
    //         return response()->json(['status' => 'error', 'message' => 'Template not found'], 404);
    //     }

    //     // Create the customized message
    //     $messageData = [
    //         'CUSTOMER_NAME' => $validated['customer_name'],
    //         'AMOUNT'        => $validated['amount'],
    //         'IP_OR_USER_NAME_OR_ID' => $validated['ip_or_user_name'],
    //         'DUE_AMOUNT'    => $validated['due_amount'],
    //         'COMPANY_NAME'  => $validated['company_name'],
    //     ];

    //     $customMessage = $this->smsService->customizeMessage($messageData, $template->template);

    //     // Send the SMS
    //     $response = $this->smsService->sendSMS($validated['recipient'], $customMessage);

    //     return response()->json($response);
    // }

    // app/Http/Controllers/SmsController.php

}
