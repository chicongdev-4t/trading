<?php

namespace App\Http\Controllers;

use App\Http\Service\AccountWithdrawalService;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountWithdrawalController extends Controller
{
    protected AccountWithdrawalService $accountwithdrawalService;

    public function __construct(AccountWithdrawalService $accountwithdrawalService)
    {
        $this->accountwithdrawalService = $accountwithdrawalService;
    }
    //
    public function createWithdrawal() {
        \request()->validate([
            'amount' => 'required',
            'nameAccount' => 'required',
            'id' => 'required',
            'user_id' => 'required',
            'balance' => 'required',
            'address' => 'required',
            'memo' => 'nullable|string'
        ]);

        $amount = request('amount');
        $nameAccount = request('nameAccount');
        $id = request('id');
        $user_id = request('user_id');
        $balance = request('balance');
        $address = request('address');
        $memo = request('memo');


        return $this->accountwithdrawalService->createWithdrawal($amount, $nameAccount, $id, $user_id, $balance, $address, $memo);
    }


    public function showAccountWithdrawal($name, $user_id) {
        $accountWithdrawals = $this->accountwithdrawalService->showAccountWithdrawal($name, $user_id)->paginate(3);
        return response()->json($accountWithdrawals);
    }
    public function cancelWithdrawal($id) {
        return $this->accountwithdrawalService->cancelWithdrawal($id);
    }
}
