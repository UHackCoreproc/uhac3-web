<?php

namespace UHacWeb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_number' => 'required',
            'account_type_id' => 'required',
        ];
    }
}
