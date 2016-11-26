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
