<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Models\Account;
use UHacWeb\Models\AccountType;
use UHacWeb\Models\Coupon;
use UHacWeb\Models\Transaction;
use UHacWeb\Notifications\SendCouponCode;
use UHacWeb\Processors\UnionBankAccountProcessor;
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

    public function makeTransaction(Request $request, $accountId)
    {
        $user = $request->user();

        if ( ! $account = $user->accounts()->where('id', $accountId)->first()) {
            return $this->response->errorUnauthorized('Invalid access to this account.');
        }


        if ($request->get('target_account_type_id') == AccountType::CODE_REDEMPTION) {
            $account = Account::where('account_number', config('ggpay.accounts.source.account_no', 101109944444))->first();
            $transaction = $this->createRedemptionCode($account, $request);
        } else {
            $transaction = $this->createTransaction($account, $request);
            $transaction = $this->processTransfer($transaction);
        }

        return $this->response->withItem($transaction, new TransactionTransformer);
    }

    private function createRedemptionCode(Account $account, Request $request)
    {
        $sourceAccountType = $request->user()->accounts()->where('account_type_id', $request->get('account_type_id'))->first();
        $transactionToOmega = $request->user()->transactions()->create([
            'source_account_id' => $sourceAccountType ? $sourceAccountType->id : null,
            'target_account_id' => $account->id,
            'target_account_type_id' => $request->get('target_account_type_id'),
            'mobile_number' => $request->get('mobile_number'),
            'amount' => $request->get('amount'),
            'remarks' => $request->get('remarks'),
            'status' => 'PENDING'
        ]);

        $transactionToOmega = $this->processTransfer($transactionToOmega);

        if ($transactionToOmega->status == 'SUCCESS') {
            $transaction = $account->transactions()->create([
                'target_account_type_id' => AccountType::CODE_REDEMPTION,
                'mobile_number' => $request->get('mobile_number'),
                'amount' => $request->get('amount'),
                'remarks' => $request->get('remarks'),
                'status' => 'PENDING'
            ]);

            $coupon = $transaction->coupon()->create([
                'sender_contact_no' => $transaction->sourceUser->mobileNumber->mobile_number,
                'recipient_contact_no' => $transaction->mobile_number,
                'amount' => $transaction->amount
            ]);

            $transaction->notify(new SendCouponCode($coupon));

            return $transaction;
        }

        return $transactionToOmega;
    }

    private function createTransaction(Account $account, Request $request)
    {
        $transaction = $account->transactions()->create([
            'source_user_id' => $request->user()->id,
            'target_account_id' => $request->get('target_account_id'),
            'target_account_type_id' => $request->get('target_account_type_id'),
            'target_user_id' => $request->get('target_user_id'),
            'mobile_number' => $request->get('mobile_number'),
            'amount' => $request->get('amount'),
            'remarks' => $request->get('remarks'),
            'status' => 'PENDING'
        ]);

        return $transaction;
    }

    private function processTransfer(Transaction $transaction)
    {
        $sourceAccount = $transaction->sourceAccount;

        if ( ! $sourceAccount) {
            return null;
        }

        $ubank = new UnionBankAccountProcessor($sourceAccount->account_number);
        $validAccount = $this->validateAccount($ubank, $transaction);

        if ( ! $validAccount) {
            $transaction->update(['status' => 'FAILED']);
            return $transaction;
        }

        $transferResponse = $ubank->transfer($transaction->amount, $transaction->targetAccount->account_number, $transaction->reference_number);

        if ( ! $transferResponse) {
            $transaction->update(['status' => 'FAILED']);
            return $transaction;
        }

        $transaction->update(['status' => 'SUCCESS']);
        return $transaction;
    }

    private function validateAccount(UnionBankAccountProcessor $ubank, Transaction $transaction)
    {
        $account = $ubank->getAccountInformation();
        if ( $account['status'] != 'ACTIVE' ) {
            return false;
        }

        if ( doubleval($account['current_balance']) <= $transaction->amount ) {
            return false;
        }

        return true;
    }

    public function redeemCode(Request $request)
    {
        $user = $request->user();
        $coupon = Coupon::where('code', $request->get('code'))
            ->where('sender_contact_no', $request->get('sender_contact_no'))
            ->where('recipient_contact_no', $request->get('recipient_contact_no'))
            ->first();

        if ( ! $coupon) {
            return $this->response->errorNotFound('Coupon reference not found or mismatch.');
        }

        $transaction = $coupon->transaction;
        $transaction->targetAccount = $user->accounts()->where('account_type_id', AccountType::BANK_ACCOUNT)->first();
        $transaction->save();

        $transaction = $this->processTransfer($transaction);

        if ($transaction->status == 'SUCCESS') {
            $coupon->update(['claimed_at' => Carbon::now()]);
        }

        return $this->response->withItem($transaction, new TransactionTransformer);
    }
}
