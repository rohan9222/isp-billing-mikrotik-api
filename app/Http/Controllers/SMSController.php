<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use Codepagol\SmsBridge\Facades\SmsBridge;

class SMSController extends Controller
{
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

            // Send the SMS
            $response = SmsBridge::to($data['recipient'])
                ->message($message)
                ->send();

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
        $response = SmsBridge::to($data['recipient'])
            ->message($message)
            ->send();

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
        $response = SmsBridge::to($data['recipient'])
            ->message($message)
            ->send();

        return $response;
    }
}
