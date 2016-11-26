<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Transaction;

class TransactionTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'sourceAccount',
        'targetAccount',
    ];

    public function transform(Transaction $transaction)
    {
        return [
            'id'             => $transaction->id,
            'mobile_number'  => $transaction->mobile_number,
            'amount'         => $transaction->amount,
            'status'         => $transaction->status,
            'remarks'        => $transaction->remarks,
            'created_at'     => $transaction->created_at,
        ];
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
