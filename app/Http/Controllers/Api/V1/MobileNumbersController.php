<?php

namespace UHacWeb\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use UHacWeb\Http\Controllers\Api\ApiController;
use UHacWeb\Http\Requests\StoreMobileNumberRequest;
use UHacWeb\Models\MobileNumber;
use UHacWeb\Transformers\MobileNumberTransformer;
use Validator;

class MobileNumbersController extends ApiController
{
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), (new StoreMobileNumberRequest)->rules());

        if ($validator->fails()) {
            return $this->response->errorWrongArgs($validator->getMessageBag()) ;
        }

        $mobileNumber = MobileNumber::firstOrNew([
            'mobile_number' => $request->get('mobile_number')
        ]);

        if ($mobileNumber->exists && $mobileNumber->user) {
            return $this->response->errorWrongArgs(new MessageBag(['user' => 'Mobile number provided is already used by another user.']));
        }

        $mobileNumber->save();

        // TODO: Send mobile code

        return $this->response->withItem($mobileNumber, new MobileNumberTransformer);
    }
}
