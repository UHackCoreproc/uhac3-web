<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    const PHILIPPINES = 174;

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
    ];

}
