<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Http\Requests\StoreUserRequest;
use UHacWeb\Models\ApiKey;
use UHacWeb\Models\Country;
use UHacWeb\Models\MobileNumber;
use UHacWeb\Models\User;
use UHacWeb\Transformers\UserTransformer;
use Validator;

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

    public function register(Request $request)
    {
        $mobileNumber = MobileNumber::where('mobile_number', $request->get('mobile_number'))
            ->where('verification_code', $request->get('mobile_number'))->first();

        // Check if mobile number and verification code are updated
        if ( ! $mobileNumber) {
            return $this->response->errorWrongArgs(new MessageBag([
                'mobile_number' => 'Mobile number and verification code mismatch.'
            ]));
        }

        $userValidator = Validator::make($request->all(), (new StoreUserRequest)->rules());

        // Check if user credentials field are valid
        if ($userValidator->fails()) {
            return $this->response->errorWrongArgs($userValidator->getMessageBag());
        }

        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('email'))
        ]);

        // Find Country
        $country = Country::where('name', 'like', "%{$request->get('country')}%")->first();

        // Create address
        $address = $user->addresses()->create([
            'label' => 'Default',
            'address_1' => $request->get('address_1'),
            'address_2' => $request->get('address_2'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip_code' => $request->get('zip_code'),
            'country_id' => isset($country->id) ? $country->id : null
        ]);

        if ($request->hasFile('image')) {
            $user->saveAvatar($request->file('image'), 'avatars');
        }

        $user->default_address_id = $address->id;
        $user->save();

        // Bind created user to verified mobile number
        $mobileNumber->user = $user;
        $mobileNumber->save();

        // Create API key for created user
        $apiKey = ApiKey::make($user);

        return $this->response->withItem($user, new UserTransformer, null, [], [
            'X-Authorization' => $apiKey->key
        ]);
    }
}
