<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use UHacWeb\Models\Mixins\VerifyMobileNumber;

class MobileNumber extends Model
{
    use Notifiable, VerifyMobileNumber;

    protected $fillable = [
        'mobile_number',
        'verification_code',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
