<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'default_address_id'
    ];

    public function defaultAddress()
    {
        return $this->hasOne(Address::class, 'id', 'default_address_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
