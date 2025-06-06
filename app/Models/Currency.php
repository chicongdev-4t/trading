<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'price'];

    public function accounts() {
        return $this->hasMany(Account::class);
    }

    public function marketOffers() {
        return $this->hasMany(MarketOffer::class);
    }

}
