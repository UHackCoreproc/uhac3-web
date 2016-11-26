<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Auth;
use Illuminate\Http\Request;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Models\ApiKey;
use UHacWeb\Transformers\UserTransformer;

class AuthController extends ApiController
{

    public function login(Request $request)
    {
        // Check if credentials is valid
        if (Auth::once($request->all())) {
            $user = Auth::getUser();
            $apiKey = ApiKey::where('apikeyable_type', get_class($user))
                ->where('apikeyable_id', $user->id)
                ->first();

            // Check if authenticated user has a valid API key assigned
            if ( ! $apiKey) {
                return $this->response->errorUnauthorized('No valid API key assigned to this user.');
            }

            return $this->response->withItem($user, new UserTransformer, null, [], [
                'X-Authorization' => $apiKey->key
            ]);
        }

        return $this->response->errorUnauthorized('Invalid credentials.');
    }
}
