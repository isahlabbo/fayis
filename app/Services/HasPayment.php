<?php
namespace App\Services;


use App\Models\Card;

trait HasPayment
{
    public function saveTransactionDetails($transaction, $invoice = null,$type=null)
    {

        $card = Card::firstOrCreate([
            "first_6digits" => $transaction['data']['card']['first_6digits'] ?? ' ',
            "last_4digits" => $transaction['data']['card']['last_4digits'] ?? ' ',
            "issuer" => $transaction['data']['card']['issuer'] ?? ' ',
            "country" => $transaction['data']['card']['country'] ?? ' ',
            "type" => $transaction['data']['card']['type'] ?? ' ',
            "token" => $transaction['data']['card']['token'] ?? ' ',
            "expiry" => $transaction['data']['card']['first_6digits'] ?? ' ',
            ]);

       $transaction = $card->transactions()->create([

            "transaction_id" => $transaction['data']['id'] ?? ' ',
            "tx_ref" => $transaction['data']['tx_ref'] ?? ' ',
            "flw_ref" => $transaction['data']['flw_ref'] ?? ' ',
            "device_fingerprint" => $transaction['data']['device_fingerprint'] ?? ' ',
            "amount" => $transaction['data']['amount'] ?? ' ',
            "currency" => $transaction['data']['currency'] ?? ' ',
            "charged_amount" => $transaction['data']['charged_amount'] ?? ' ',
            "app_fee" => $transaction['data']['app_fee'] ?? ' ',
            "merchant_fee" => $transaction['data']['merchant_fee'] ?? ' ',
            "processor_response" => $transaction['data']['processor_response'] ?? ' ',
            "auth_model" => $transaction['data']['auth_model'] ?? ' ',
            "ip_address" => $transaction['data']['ip'] ?? ' ',
            "narration" => $transaction['data']['narration'] ?? ' ',
            "status" => $transaction['data']['status'] ?? ' ',
            "payment_type" => $transaction['data']['payment_type'] ?? ' ',
            "account_id" => $transaction['data']['account_id'] ?? ' ',
            "type"=>$type
        ]); 
        if($invoice){
            $transaction->update(["invoice_id" => $invoice->id,]);
        } 

        return $transaction;  
    }
}