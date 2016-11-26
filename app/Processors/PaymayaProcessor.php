<?php


namespace UHacWeb\Processors;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use UHacWeb\Models\User;

class PaymayaProcessor {
    public $errors = [];

    /**
     * @param \UHacWeb\Models\User $user
     *
     * @return string|null
     */
    public function createCustomer(User $user)
    {
        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.paymaya.base_url') . '/customers', [
                'auth' => [config('ggpay.apis.paymaya.secret'), ''],
                'json' => [
                    'firstName'      => $user->first_name,
                    'middleName'     => '',
                    'lastName'       => $user->last_name,
                    'birthday'       => $user->birthday,
                    'sex'            => $user->sex,
                    'contact'        => [
                        'phone' => $user->mobileNumber->mobile_number,
                        'email' => $user->email
                    ],
                    'billingAddress' => [
                        'line1'       => $user->defaultAddress->address_1,
                        'line2'       => $user->defaultAddress->address_2,
                        'city'        => $user->defaultAddress->city,
                        'state'       => $user->defaultAddress->state,
                        'zipCode'     => $user->defaultAddress->zip_code,
                        'countryCode' => $user->defaultAddress->country->code
                    ],
                    'metadata'       => []
                ]
            ]);
        } catch (ClientException $e) {
            $this->errors[] = 'Invalid parameters set';

            return null;
        } catch (Exception $e) {
            $this->errors[] = 'Failed to create customer';

            return null;
        }

        if ($response->getStatusCode() == '200') {

            return (json_decode($response->getBody()->getContents()))->id;
        }

        $this->errors[] = 'Unknown Error';

        return null;
    }

    /**
     * @param $credit_card_number
     * @param $expMonth
     * @param $expYear
     * @param $cvc
     *
     * @return string|null
     */
    private function createToken($credit_card_number, $expMonth, $expYear, $cvc)
    {
        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.paymaya.base_url') . '/payment-tokens', [
                'auth' => [config('ggpay.apis.paymaya.public'), ''],
                'json' => [
                    'card' => [
                        'number'   => $credit_card_number,
                        'expMonth' => $expMonth,
                        'expYear'  => $expYear,
                        'cvc'      => $cvc
                    ],
                ]
            ]);
        } catch (Exception $e) {
            return null;
        }

        $r = json_decode($response->getBody()->getContents());

        return $r->state == 'AVAILABLE' ? $r->paymentTokenId : null;
    }

    /**
     * @param $credit_card_number
     * @param $expMonth
     * @param $expYear
     * @param $cvc
     * @param $customer_id
     *
     * @return array|null
     */
    public function addCardToCustomer($credit_card_number, $expMonth, $expYear, $cvc, $customer_id)
    {
        $token = $this->createToken($credit_card_number, $expMonth, $expYear, $cvc);

        if (!isset($token)) {
            $this->errors[] = 'Failed to add card to customer';

            return null;
        }

        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.paymaya.base_url') . '/customers/' . $customer_id . '/cards', [
                'auth' => [config('ggpay.apis.paymaya.secret'), ''],
                'json' => [
                    'paymentTokenId' => $token,
                    'isDefault'      => true,
                    'redirectUrl'    => [
                        'success' => 'https://ggpay.coreproc.ph',
                        'failure' => 'https://ggpay.coreproc.ph',
                        'cancel'  => 'https://ggpay.coreproc.ph',
                    ]
                ]
            ]);

            $r = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            $this->errors[] = 'Failed to add card to customer';

            return null;
        }

        return [
            'token'           => $r->cardTokenId,
            'verificationUrl' => $r->verificationUrl,
            'cardType'        => $r->cardType,
            'masked'          => $r->maskedPan,
        ];
    }

    /**
     * @param $customer_id
     * @param $card_id
     * @param $amount
     *
     * @return bool
     */
    public function chargeToCard($customer_id, $card_id, $amount)
    {
        $client = new Client();

        try {
            $response = $client->post(config('ggpay.apis.paymaya.base_url') . '/customers/' . $customer_id . '/cards/' . $card_id . '/payments', [
                'auth' => [config('ggpay.apis.paymaya.secret'), ''],
                'json' => [
                    'totalAmount' => [
                        'amount'   => $amount,
                        'currency' => 'PHP'
                    ]
                ]
            ]);

            $r = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            $this->errors[] = 'Failed to charge card';

            return false;
        }

        return true;
    }
}
