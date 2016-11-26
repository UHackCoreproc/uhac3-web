<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\UserProfile;

class UserProfileTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'defaultAddress'
    ];

    public function transform(UserProfile $userProfile)
    {
        return [
            'first_name' => $userProfile->first_name,
            'last_name' => $userProfile->last_name
        ];
    }

    public function includeDefaultAddress(UserProfile $userProfile)
    {
        if ($userProfile->defaultAddress) {
            return $this->item($userProfile->defaultAddress, new AddressTransformer);
        }
    }
}