<?php

namespace App\Http\Controllers;

use App\Http\Service\AccountChangeService;
use Illuminate\Http\Request;

class AccountChangeController extends Controller
{
    protected AccountChangeService $accountChangeService;


    public function __construct(AccountChangeService $accountChangeService)
    {
        $this->accountChangeService = $accountChangeService;
    }

}
