<?php
namespace Maishapay\Transactions;

use Maishapay\Util\Utils;

class Transaction
{
    protected $transaction_id; //Transaction id
    protected $transaction_uuid; //Transaction id
    protected $source_account_uuid; //Source UUID
    protected $source_account_phone_area_code; //Source Phone area code
    protected $source_account_phone_number; //Source Phone number
    protected $source_account_names; //Source Names (Expeditaire)
    protected $source_account_balance; //Source Account balance
    protected $source_account_currency; //Source Account balance currency
    protected $destination_account_uuid; //Destination UUID
    protected $destination_account_phone_area_code; //Destination Phone area code
    protected $destination_account_phone_number; //Destination Phone number
    protected $destination_account_names; //Destination Names of source account(Destination)
    protected $destination_account_balance; //Destination Account balance
    protected $destination_account_currency; //Destination Account balance currency
    protected $transaction_amount; //Amount in transaction
    protected $transaction_currency; //Amount currency in transaction
    protected $source_description;
    protected $destination_description;
    protected $transaction_fee; //Frais de transfer
    protected $transaction_rate; //Taux d'echange
    protected $source_latitude;
    protected $source_longitude;
    protected $transaction_date;
    protected $transaction_type; //ACCOUNT_TO_SAVING, ACCOUNT_TO_ACCOUNT, ACCOUNT_TO_OTHER, BILL_PAYMENT, AIRTIME, DIRECT_DEBIT, PURCHASE, CASH_WITHDRAWAL, CASH_DEPOSIT, LINKED_ACCOUNT_TRANSFER
    protected $periode_type;
    protected $periode_start_date;
    protected $periode_end_date;
    protected $number_of_time;
    protected $operation_status;
    protected $created;
    protected $updated;

    public function __construct(array $data)
    {
        $this->transaction_id = $data['transaction_id'] ?? null;
        $this->transaction_uuid = $data['transaction_uuid'] ?? null;
        $this->source_account_uuid = $data['source_account_uuid'] ?? null;
        $this->source_account_phone_area_code = $data['source_account_phone_area_code'] ?? null;
        $this->source_account_phone_number = $data['source_account_phone_number'] ?? null;
        $this->source_account_names = $data['source_account_names'] ?? null;
        $this->source_account_balance = $data['source_account_balance'] ?? null;
        $this->source_account_currency = $data['source_account_currency'] ?? null;
        $this->destination_account_uuid = $data['destination_account_uuid'] ?? null;
        $this->destination_account_phone_area_code = $data['destination_account_phone_area_code'] ?? null;
        $this->destination_account_phone_number = $data['destination_account_phone_number'] ?? null;
        $this->destination_account_names = $data['destination_account_names'] ?? null;
        $this->destination_account_balance = $data['destination_account_balance'] ?? null;
        $this->destination_account_currency = $data['destination_account_currency'] ?? null;
        $this->transaction_amount = $data['transaction_amount'] ?? null;
        $this->transaction_currency = $data['transaction_currency'] ?? null;
        $this->destination_description = $data['destination_description'] ?? null;
        $this->source_description = $data['source_description'] ?? null;
        $this->transaction_fee = $data['transaction_fee'] ?? null;
        $this->transaction_rate = $data['transaction_rate'] ?? null;
        $this->source_latitude = $data['source_latitude'] ?? null;
        $this->source_longitude = $data['source_longitude'] ?? null;
        $this->transaction_date = $data['transaction_date'] ?? null;
        $this->transaction_type = $data['transaction_type'] ?? null;
        $this->periode_type = $data['periode_type'] ?? null;
        $this->periode_start_date = $data['periode_start_date'] ?? null;
        $this->periode_end_date = $data['periode_end_date'] ?? null;
        $this->number_of_time = $data['number_of_time'] ?? null;
        $this->operation_status = $data['operation_status'] ?? null;
        $this->created = $data['created'] ?? null;
        $this->updated = $data['updated'] ?? null;

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        if (!$this->transaction_uuid) {
            $this->transaction_uuid = Utils::uuid("client-id");
        }

        if (!strtotime($this->created)) {
            $this->created = $now;
        }

        if (!strtotime($this->updated)) {
            $this->updated = $now;
        }
    }

    public function getArrayCopy()
    {
        return [
            'transaction_id' => $this->transaction_id,
            'transaction_uuid' => $this->transaction_uuid,
            'source_account_uuid' => $this->source_account_uuid,
            'source_account_phone_area_code' => $this->source_account_phone_area_code,
            'source_account_phone_number' => $this->source_account_phone_number,
            'source_account_names' => $this->source_account_names,
            'source_account_balance' => $this->source_account_balance,
            'source_account_currency' => $this->source_account_currency,
            'destination_account_uuid' => $this->destination_account_uuid,
            'destination_account_phone_area_code' => $this->destination_account_phone_area_code,
            'destination_account_phone_number' => $this->destination_account_phone_number,
            'destination_account_names' => $this->destination_account_names,
            'destination_account_balance' => $this->destination_account_balance,
            'destination_account_currency' => $this->destination_account_currency,
            'transaction_amount' => $this->transaction_amount,
            'transaction_currency' => $this->transaction_currency,
            'destination_description' => $this->destination_description,
            'source_description' => $this->source_description,
            'transaction_fee' => $this->transaction_fee,
            'transaction_rate' => $this->transaction_rate,
            'source_latitude' => $this->source_latitude,
            'source_longitude' => $this->source_longitude,
            'transaction_date' => $this->transaction_date,
            'transaction_type' => $this->transaction_type,
            'periode_type' => $this->periode_type,
            'periode_start_date' => $this->periode_start_date,
            'periode_end_date' => $this->periode_end_date,
            'number_of_time' => $this->number_of_time,
            'operation_status' => $this->operation_status,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }

    public function update($data)
    {
        $this->transaction_uuid = $data['transaction_uuid'] ?? $this->transaction_uuid;
        $this->source_account_uuid = $data['source_account_uuid'] ??  $this->source_account_uuid;
        $this->source_account_phone_area_code = $data['source_account_phone_area_code'] ?? $this->source_account_phone_area_code;
        $this->source_account_phone_number = $data['source_account_phone_number'] ?? $this->source_account_phone_number;
        $this->source_account_names = $data['source_account_names'] ?? $this->source_account_names;
        $this->source_account_balance = $data['source_account_balance'] ?? $this->source_account_balance;
        $this->source_account_currency = $data['source_account_currency'] ?? $this->source_account_currency;
        $this->destination_account_uuid = $data['destination_account_uuid'] ?? $this->destination_account_uuid;
        $this->destination_account_phone_area_code = $data['destination_account_phone_area_code'] ?? $this->destination_account_phone_area_code;
        $this->destination_account_phone_number = $data['destination_account_phone_number'] ?? $this->destination_account_phone_number;
        $this->destination_account_names = $data['destination_account_names'] ?? $this->destination_account_names;
        $this->destination_account_balance = $data['destination_account_balance'] ?? $this->destination_account_balance;
        $this->destination_account_currency = $data['destination_account_currency'] ?? $this->destination_account_currency;
        $this->transaction_amount = $data['transaction_amount'] ?? $this->transaction_amount;
        $this->transaction_currency = $data['transaction_currency'] ?? $this->transaction_currency;
        $this->destination_description = $data['destination_description'] ?? $this->destination_description;
        $this->source_description = $data['source_description'] ?? $this->source_description;
        $this->transaction_fee = $data['transaction_fee'] ?? $this->transaction_fee;
        $this->transaction_rate = $data['transaction_rate'] ?? $this->transaction_rate;
        $this->source_latitude = $data['source_latitude'] ?? $this->source_latitude;
        $this->source_longitude = $data['source_longitude'] ?? $this->source_longitude;
        $this->transaction_date = $data['transaction_date'] ?? $this->transaction_date;
        $this->transaction_type = $data['transaction_type'] ?? $this->transaction_type;
        $this->periode_type = $data['periode_type'] ?? $this->periode_type;
        $this->periode_start_date = $data['periode_start_date'] ?? $this->periode_start_date;
        $this->periode_end_date = $data['periode_end_date'] ?? $this->periode_end_date;
        $this->number_of_time = $data['number_of_time'] ?? $this->number_of_time;
        $this->operation_status = $data['operation_status'] ?? $this->operation_status;
    }
}
