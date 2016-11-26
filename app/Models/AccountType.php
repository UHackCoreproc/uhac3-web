<?php

namespace UHacWeb\Models;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{

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
