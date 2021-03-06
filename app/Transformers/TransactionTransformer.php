<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Transaction;

class TransactionTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'coupon',
        'sourceAccount',
        'targetAccount',
    ];

    public function transform(Transaction $transaction)
    {
        return [
            'id'               => $transaction->id,
            'mobile_number'    => $transaction->mobile_number,
            'amount'           => $transaction->amount,
            'status'           => $transaction->status,
            'remarks'          => @($transaction->remarks) ?: '',
            'reference_number' => @($transaction->reference_number) ?: '',
            'confirmation_no'  => @($transaction->confirmation_no) ?: '',
            'created_at'       => $transaction->created_at,
        ];
    }

    public function includeCoupon(Transaction $transaction)
    {
        if ($transaction->coupon) {
            return $this->item($transaction->coupon, new CouponTransformer);
        }
    }

    public function includeSourceAccount(Transaction $transaction)
    {
        if ($transaction->sourceAccount) {
            return $this->item($transaction->sourceAccount, new AccountTransformer);
        }
    }

    public function includeTargetAccount(Transaction $transaction)
    {
        if ($transaction->targetAccount) {
            return $this->item($transaction->targetAccount, new AccountTransformer);
        }
    }
}
