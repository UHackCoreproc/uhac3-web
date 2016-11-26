<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Models\Account;
use UHacWeb\Models\AccountType;
use UHacWeb\Models\Transaction;
use UHacWeb\Notifications\SendCouponCode;
use UHacWeb\Transformers\TransactionTransformer;

class TransactionsController extends ApiController
{

    public function index(Request $request, $accountId = null)
    {
        $user = $request->user();
        $transactions = $user->transactions();

        if ( ! $accountId) {
            $transactions->where('source_account_id', $accountId);
        }

        return $this->response->withPaginator($transactions->paginate(10), new TransactionTransformer);
    }

    public function make(Request $request)
    {
        $user = $request->user();
        $accountId = $request->get('source_account_id');

        if ( ! $account = $user->accounts()->where('id', $accountId)->first()) {
            return $this->response->errorUnauthorized('Invalid access to this account.');
        }

        $transaction = $this->createTransaction($account, $request);

        if ($request->get('account_type_id') == AccountType::CODE_REDEMPTION) {
            $this->createRedemptionCode($transaction, $request);
        }

        return $this->response->withItem($transaction, new TransactionTransformer);
    }

    private function createRedemptionCode(Transaction $transaction)
    {
        $coupon = $transaction->coupon()->create([
            'sender_contact_no' => $transaction->user->mobileNumber->mobile_number,
            'recipient_contact_no' => $transaction->mobile_number,
            'amount' => $transaction->amount
        ]);

        $transaction->notify(new SendCouponCode($coupon));
    }

    private function createTransaction(Account $account, Request $request)
    {
        return $account->transactions()->create([
            'source_user_id' => $request->get('source_user_id'),
            'target_account_id' => $request->get('target_account_id'),
            'target_account_type_id' => $request->get('target_account_type_id'),
            'target_user_id' => $request->get('target_user_id'),
            'amount' => $request->get('amount'),
            'remarks' => $request->get('remarks'),
        ]);
    }

}
