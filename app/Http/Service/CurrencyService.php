<?php

namespace App\Http\Service;

use App\Models\Currency;

class CurrencyService
{
    public function getByCode(string $code)
    {
        return Currency::query()->firstWhere('code', $code);
    }

    public function getAllCurrency() {
        $currencies = Currency::query()->get();
        return $currencies;
    }

    public function getAllCodeCurrencyExcludeUSDT() {
        return Currency::query()->where('code', '!=', 'USDT')->pluck('code');
    }
}
