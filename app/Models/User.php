<?php

namespace UHacWeb\Models;

use Gravatar;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use UHacWeb\Models\Mixins\HasAvatar;

class User extends Authenticatable
{
    use HasAvatar, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'default_address_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class, 'id', 'default_address_id');
    }

    public function getAvatarUrl()
    {
        if (empty($this->avatar)) {
            return Gravatar::get($this->email);
        }

        return $this->avatar->url();
    }

    public function mobileNumber()
    {
        return $this->hasOne(MobileNumber::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
