<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\User;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'email'         => $user->email,
            'name'          => $user->name,
            'created_at'    => $user->created_at,
            'updated_at'    => $user->updated_at,
        ];
    }

}