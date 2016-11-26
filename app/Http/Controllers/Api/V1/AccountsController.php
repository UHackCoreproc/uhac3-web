<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Http\Requests\StoreUpdateAccountRequest;
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
        $user = $request->user();
        $validator = Validator::make($request->all(), (new StoreUpdateAccountRequest)->rules());

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->getMessageBag());
        }

        $account = $user->accounts()->create($request->all());

        return $this->response->withItem($account, new AccountTransformer);
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
