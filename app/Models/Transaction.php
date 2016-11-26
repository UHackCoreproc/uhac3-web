<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;
use UHacWeb\Models\Mixins\GenerateReferenceNumber;

class Transaction extends Model
{
    use GenerateReferenceNumber;

    protected $fillable = [
        'user_id',
        'source_account_id',
        'target_account_id',
        'target_account_type_id',
        'type',
        'mobile_number',
        'reference_number',
        'amount',
        'status',
        'remarks',
    ];

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
