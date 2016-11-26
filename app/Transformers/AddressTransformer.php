<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Address;

class AddressTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'country'
    ];

    public function transform(Address $address)
    {
        return [
            'label' => $address->label,
            'address_1' => $address->address_1,
            'address_2' => $address->address_2,
            'city' => $address->city,
            'state' => $address->state,
            'zip_code' => $address->zip_code
        ];
    }

    public function includeCountry(Address $address)
    {
        if ($address->country) {
            return $this->item($address->country, new CountryTransformer);
        }
    }
}