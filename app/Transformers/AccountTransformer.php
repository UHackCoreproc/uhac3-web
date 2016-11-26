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
         $accountTransform = [
            'id'              => $account->id,
            'title'           => $account->title,
            'description'     => $account->description,
            'account_number'  => $account->account_number,
         ];

        if ($account->paymaya_customer_id && $account->paymaya_card_id) {
            $accountTransform['paymaya_customer_id'] = $account->paymaya_customer_id;
            $accountTransform['paymaya_card_id'] = $account->paymaya_card_id;
            $accountTransform['paymaya_is_verified'] = $account->paymaya_is_verified;
            $accountTransform['paymaya_verify_url'] = $account->paymaya_verify_url;
            $accountTransform['paymaya_card_type'] = $account->paymaya_card_type;
            $accountTransform['paymaya_card_mask'] = $account->paymaya_card_mask;
        }

        return $accountTransform;
    }

    public function includeAccountType(Account $account)
    {
        if ($account->accountType) {
            return $this->item($account->accountType, new AccountTypeTransformer);
        }
    }
}