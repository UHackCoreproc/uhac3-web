<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    protected $fillable = [
        'user_id',
        'account_type_id',
        'account_number',
        'title',
        'description',
        'paymaya_customer_id',
        'paymaya_card_id',
        'paymaya_is_verified',
        'paymaya_verify_url',
        'paymaya_card_type',
        'paymaya_card_mask',
    ];

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'source_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
