<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Account;

class AccountTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'accountType'
    ];

    public function transform(Account $account)
    {
        return [
            'id'              => $account->id,
            'title'           => $account->title,
            'description'     => $account->description,
            'account_number'  => $account->account_number,
        ];
    }

    public function includeAccountType(Account $account)
    {
        if ($account->accountType) {
            return $this->item($account->accountType, new AccountTypeTransformer);
        }
    }
}