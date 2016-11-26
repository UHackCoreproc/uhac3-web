<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use UHacWeb\Models\Mixins\GeneratesCouponCode;

class Coupon extends Model
{
    use GeneratesCouponCode;

    protected $fillable = [
        'sender_contact_no',
        'recipient_contact_no',
        'code',
        'amount',
        'transaction_id',
        'claimed_at',
    ];

    protected $dates = [
        'claimed_at'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
