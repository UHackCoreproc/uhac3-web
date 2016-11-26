<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\User;

class UserTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'userProfile'
    ];

    public function transform(User $user)
    {
        return [
            'email'         => $user->email,
            'created_at'    => $user->created_at,
            'updated_at'    => $user->updated_at,
        ];
    }

    public function includeUserProfile(User $user)
    {
        if ($user->userProfile) {
            return $this->item($user->userProfile, new UserProfileTransformer);
        }
    }
}