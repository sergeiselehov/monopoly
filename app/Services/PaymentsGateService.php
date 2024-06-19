<?php

namespace App\Services;

use GuzzleHttp\Client;

class PaymentsGateService
{
    private $url;
    const CREATE_PAYMENT_AND_GET_CARD_URL = 'p2p_transactions';
    public function __construct()
    {
        $this->url = config('payments_gate.base_url');
        $this->secret_key = config('payments_gate.key');
    }

    public function createPaymentAndGetCard(array $params):bool|object
    {
        $client = new Client();
        $res = $client->post(
            $this->url.self::CREATE_PAYMENT_AND_GET_CARD_URL,
            [
                'headers'=>[
                    'Signature'=>$this->prepareSign($params)
                ],
                'json'=>$params
            ]
        );
        return $this->checkResult($res);

    }

    public function confirmAsPayed(string $transaction_id,array $params):bool|object
    {
        $client = new Client();
        $result = $client->put(
            $this->url.$transaction_id."/paid",
            [
                'headers'=>[
                    'Signature'=>$this->prepareSign($params)
                ],
                'json'=>$params
            ]
        );
        return $this->checkResult($result);
    }

    public function cancelPayment(string $transaction_id,array $params):bool|object
    {
        $client = new Client();
        $result = $client->put(
            $this->url.$transaction_id."/cancel",
            [
                'headers'=>[
                    'Signature'=>$this->prepareSign($params)
                ],
                'json'=>$params
            ]
        );
        return $this->checkResult($result);
    }


    public function getTransactionData(string $transaction_id,array $params):bool|object
    {
        $client = new Client();
        $result = $client->get(
            $this->url.$transaction_id,
            [
                'headers'=>[
                    'Signature'=>$this->prepareSign($params)
                ],
                'json'=>$params
            ]
        );
        return $this->checkResult($result);
    }


    private function prepareSign(array $params):string
    {
        return sha1($this->secret_key.json_encode($params));
    }

    private function checkResult($result):bool|object
    {
        if ($result->getStatusCode() !== 200) {
            return false;
        }
        $data = json_decode($result->getBody());
        if (empty($data) || empty($data->result_code)) {
            return false;
        }
        return $data->payload;
    }

}
