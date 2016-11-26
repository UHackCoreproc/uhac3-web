<?php


namespace UHacWeb\Processors;


use Exception;
use GuzzleHttp\Client;

class UnionBankAccountProcessor {
    public function __construct($account_no)
    {
        $this->account_no = $account_no;
    }

    /**
     * @return array|null
     */
    public function getAccountInformation()
    {
        $client = new Client();

        try {
            $response = $client->get(config('ggpay.apis.unionbank.base_url') . '/getAccount', [
                'headers' => [
                    'x-ibm-client-id'     => config('ggpay.apis.unionbank.client_id'),
                    'x-ibm-client-secret' => config('ggpay.apis.unionbank.secret_key')
                ],
                'query'   => [
                    'account_no' => $this->account_no
                ]
            ]);

            $r = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            return null;
        }

        return [
            'status'            => $r->status,
            'account_name'      => $r->account_name,
            'currency'          => $r->currency,
            'available_balance' => $r->available_balance,
            'current_balance'   => $r->current_balance
        ];
    }

    /**
     * @param $amount
     * @param $target_account_no
     * @param $ref_no
     *
     * @return array|null
     */
    public function transfer($amount, $target_account_no, $ref_no)
    {
        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.unionbank.base_url'), [
                'headers' => [
                    'x-ibm-client-id'     => config('ggpay.apis.unionbank.client_id'),
                    'x-ibm-client-secret' => config('ggpay.apis.unionbank.secret_key')
                ],
                'json'    => [
                    'channel_id'      => 'BLUEMIX',
                    'transaction_id'  => $ref_no,
                    'source_account'  => $this->account_no,
                    'source_currency' => 'php',
                    'target_account'  => $target_account_no,
                    'target_currency' => 'php',
                    'amount'          => $amount
                ]
            ]);

            $r = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            return null;
        }

        return [
            'confirmation_no' => $r->confirmation_no,
            'status'          => $r->status == 'S' ? 'SUCCESS' : 'FAILED'
        ];
    }

    public function transferToOmega($amount)
    {
        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.unionbank.base_url'), [
                'headers' => [
                    'x-ibm-client-id'     => config('ggpay.apis.unionbank.client_id'),
                    'x-ibm-client-secret' => config('ggpay.apis.unionbank.secret_key')
                ],
                'json'    => [
                    'channel_id'      => 'BLUEMIX',
                    'transaction_id'  => uniqid('GG-'),
                    'source_account'  => $this->account_no,
                    'source_currency' => 'php',
                    'target_account'  => config('ggpay.accounts.source.account_no'),
                    'target_currency' => 'php',
                    'amount'          => $amount
                ]
            ]);

            $r = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            return null;
        }

        return [
            'confirmation_no' => $r->confirmation_no,
            'status'          => $r->status == 'S' ? 'SUCCESS' : 'FAILED'
        ];
    }
}
