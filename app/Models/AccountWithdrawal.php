<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class AccountWithdrawal extends Model
{

    const StatusPending = 'Pending';

    use HasFactory;
    protected $fillable = ['account_id', 'name', 'amount', 'user_id', 'status', 'destination'];

    protected $casts = [
        'destination' => 'array', // Chuyển JSON thành mảng PHP
    ];
    public function accountWithdrawal() {
        return $this->belongsTo(Account::class);
    }
}
