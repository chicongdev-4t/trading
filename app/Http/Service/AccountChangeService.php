<?php

namespace App\Http\Service;

use App\Models\AccountChange;

class AccountChangeService
{

    /**
     * lay account change tren user login
     */
//    public function getAll() {
//        $user = auth()->user();
//
//        $accounts = $user->accounts;
//        $transactions = [];
//        foreach($accounts as $account) {
//            foreach($account->accountChanges as $accountChange) {
//                   $transactions[] = [
//                       'type' => $accountChange->type,
//                       'code' => $accountChange->currency,
//                       'before' => $accountChange->before_balance,
//                       'change' => $accountChange->change,
//                       'after' => $accountChange->after_balance,
//                       'note' => $accountChange->note,
//                       'time' => $accountChange->updated_at
//                   ];
//            }
//        }
//
//        usort($transactions, function($a, $b) {
//            return $b['time'] <=> $a['time'];
//        });
//
//
//        return $transactions;
//    }

    public function getAll()
    {
        return AccountChange::query()
            ->whereIn('account_id', auth()->user()->accounts->pluck('id'))
            ->orderBy('updated_at', 'desc');
    }

}
