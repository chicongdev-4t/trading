<?php

namespace App\Http\Controllers;

use App\Http\Service\AccountService;
use Illuminate\Http\Request;


class AccountController extends Controller
{
    //

    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function indexWallet() {
        $user = auth()->user();
        $accounts = $user->accounts;
        $accountUSDT = 0;
        foreach($accounts as $account) {
            if($account->name == 'USDT') {
                $accountUSDT = $account;
            }
        }
        return view('components.trading.wallet', ['coins'=>$this->accountService->listCoinUserLoginUser(),
            'accountUSDT' => $accountUSDT
        ]);
    }

}
