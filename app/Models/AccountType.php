<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    const BANK_ACCOUNT = 1;
    const DEBIT_CREDIT = 2;
    const CODE_REDEMPTION = 3;

    protected $fillable = [
        'name',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function targetTransactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'target_account_type_id');
    }
}
