<?php

namespace App\Http\Controllers;

use App\Http\Service\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService) {
        $this->currencyService = $currencyService;
    }
    //
    public function getAllCodeCurrencyExcludeUSDT() {
        return $this->currencyService->getAllCurrency();
    }
}
