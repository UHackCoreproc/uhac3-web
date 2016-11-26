<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Http\Requests\StoreUpdateAccountRequest;
use UHacWeb\Models\AccountType;
use UHacWeb\Processors\PaymayaProcessor;
use UHacWeb\Transformers\AccountTransformer;
use Validator;

class AccountsController extends ApiController
{

    public function index(Request $request)
    {
        $user = $request->user();

        return $this->response->withCollection($user->accounts, new AccountTransformer);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new StoreUpdateAccountRequest)->rules());

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->getMessageBag());
        }

        $accountType = $request->get('account_type');

        switch ($accountType) {
            case AccountType::BANK_ACCOUNT:
                $account = $this->storeBankAccount($request);
                break;
            case AccountType::DEBIT_CREDIT:
                $account = $this->addToPayMayaCard($request);
                break;
            default:
                return $this->response->errorMethodNotAllowed('Invalid account type.');
        }

        if ( ! $account) {
            return $this->response->errorInternalError('An error has occurred while trying to add a new account.');
        }

        return $this->response->withItem($account, new AccountTransformer);
    }

    private function storeBankAccount(Request $request)
    {
        $user = $request->user();
        $accountType = $request->get('account_type');

        return $user->accounts()->create([
            "title" => $request->get('title'),
            "description" => $request->get('description'),
            "account_number" => $request->get('account_number'),
            "account_type_id" => $accountType,
        ]);
    }

    private function addToPayMayaCard(Request $request)
    {
        $user = $request->user();
        $accountType = $request->get('account_type');
        $accountData = [
            "title" => $request->get('title'),
            "description" => $request->get('description'),
            "account_number" => $request->get('account_number'),
            "account_type_id" => $accountType,
        ];

        $paymayaResponse = $this->processPayMaya($request);

        if ($paymayaResponse) {
            return $user->accounts()->create(array_merge($accountData, $paymayaResponse));
        }

        return null;
    }

    private function processPayMaya(Request $request)
    {
        $processor = new PaymayaProcessor;

        $customer = $processor->createCustomer($request->user());

        $creditCardNumber = $request->get('account_number');
        $expMonth = $request->get('exp_month');
        $expYear = $request->get('exp_year');
        $cvc = $request->get('cvc');

        $processCard = $processor->addCardToCustomer($creditCardNumber, $expMonth, $expYear, $cvc, $customer);

        if (!$processCard) {
            return null;
        }

        return [
            'paymaya_is_verified' => false,
            'paymaya_customer_id' => $customer,
            'paymaya_verify_url' => $processCard['verificationUrl'],
            'paymaya_card_id' => $processCard['cardId'],
            'paymaya_card_type' => $processCard['cardType'],
            'paymaya_card_mask' => $processCard['masked']
        ];
    }

    public function show(Request $request, $accountId)
    {
        $user = $request->user();
        $account = $user->accounts()->where('id', $accountId)->first();

        if ( ! $account) {
            return $this->response->errorNotFound('Account not found.');
        }

        return $this->response->withItem($account, new AccountTransformer);
    }

    public function update(Request $request, $accountId)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), (new StoreUpdateAccountRequest)->rules());

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->getMessageBag());
        }

        $account = $user->accounts()->where('id', $accountId)->first();

        if ( ! $account) {
            return $this->response->errorNotFound('Account not found.');
        }

        $account->update($request->all());

        return $this->response->withItem($account, new AccountTransformer);
    }

    public function destroy(Request $request, $accountId)
    {
        $user = $request->user();
        $account = $user->accounts()->where('id', $accountId)->first();

        if ( ! $account) {
            return $this->response->errorNotFound('Account not found.');
        }

        $account->delete();
    }
}
