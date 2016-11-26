<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\AccountType;

class AccountTypeTransformer extends TransformerAbstract
{

    public function transform(AccountType $accountType)
    {
        return [
            'id'    => $accountType->id,
            'name'  => $accountType->name,
        ];
    }
}