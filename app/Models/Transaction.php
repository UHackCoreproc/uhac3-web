<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use UHacWeb\Models\Mixins\GeneratesReferenceNumber;

class Transaction extends Model
{
    use GeneratesReferenceNumber, Notifiable;

    protected $fillable = [
        'source_user_id',
        'source_account_id',
        'target_account_id',
        'target_user_id',
        'target_account_type_id',
        'mobile_number',
        'reference_number',
        'amount',
        'status',
        'remarks',
    ];

    public function coupon()
    {
        return $this->hasOne(Coupon::class);
    }

    public function sourceAccount()
    {
        return $this->belongsTo(Account::class, 'id', 'source_account_id');
    }

    public function targetAccount()
    {
        return $this->belongsTo(Account::class, 'id', 'target_account_id');
    }

    public function targetAccountType()
    {
        return $this->belongsTo(AccountType::class, 'id', 'target_account_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
