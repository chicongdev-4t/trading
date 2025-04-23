<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'currency_id', 'name', 'balance'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function accountChanges() {
        return $this->hasMany(AccountChange::class);
    }

    public function accountWithdrawals() {
        return $this->hasMany(AccountWithdrawal::class);
    }
}
