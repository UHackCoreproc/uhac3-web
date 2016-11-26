<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\User;

class UserTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'mobileNumber',
        'defaultAddress'
    ];

    public function transform(User $user)
    {
        return [
            'email'         => $user->email,
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'created_at'    => $user->created_at,
            'updated_at'    => $user->updated_at,
        ];
    }

    public function includeDefaultAddress(User $user)
    {
        if ($user->defaultAddress) {
            return $this->item($user->defaultAddress, new AddressTransformer);
        }
    }

    public function includeMobileNumber(User $user)
    {
        if ($user->mobileNumber) {
            return $this->item($user->mobileNumber, new MobileNumberTransformer);
        }
    }
}