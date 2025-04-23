<?php

namespace App\Http\Service;

use App\Models\Account;
use App\Models\AccountWithdrawal;
use App\Models\User;

class AccountWithdrawalService
{

    public function validateAmount($amount) {
        if($amount <= 0) {
            throw new \Exception('amount not found');
        }
    }

    public function validateAmountAndAmountAccount($amount, $balance) {
        if($amount > $balance) {
            throw new \Exception('amount must be less than balance ');
        }
    }

    public function getAmountAvailable(string $nameAccount, int $id, float $balance) {
        $amountUnfinisheds = AccountWithdrawal::query()->where('name', $nameAccount)
                                                       ->where('account_id', $id)
                                                       ->whereIn('status', ['Pending', 'Canceled', 'Failed', 'Processing', 'Completed'])->get();
        $totalAmountUnfinisheds = 0;
        foreach($amountUnfinisheds as $amountUnfinished) {
            $totalAmountUnfinisheds = $totalAmountUnfinisheds + $amountUnfinished->amount;
        }

        return $balance - $totalAmountUnfinisheds;
    }
    /**
     * user a rut 5 TON tu account TON vao dia chi b voi memo c
     */
    public function createWithdrawal(float $amount, string $nameAccount, int $id, int $user_id, float $balance, string $address, string $memo) {
        /**
         * - kiem tra xem amount > 0
         * - kiem tra xem amount < amount account
         * - kiem tra xem amout < amount account - amount (status=Pending, Canceled, Processing, Failed, Completed )
         * - tạo lệnh rút
         */

        //kiem tra xem amount > 0
        $this->validateAmount($amount);

        // kiem tra xem amount < amount account
        $this->validateAmountAndAmountAccount($amount, $balance);

        //- kiem tra xem amout < amount account - amount (status=Pending, Canceled, Processing, Failed, Completed )
        $amountAvailable = $this->getAmountAvailable($nameAccount, $id, $balance);

        if($amount > $amountAvailable) {
            throw new \Exception('Amount less than available amount, cannot be withdrawn ');
        }

        // tao lenh rut
        if($nameAccount == 'TON') {
            $destination = [
               'address' => $address,
               'memo' => $memo
            ];
            return AccountWithdrawal::create([
                'account_id' => $id,
                'name' => $nameAccount,
                'amount' => $amount,
                'user_id' => $user_id,
                'status' =>  AccountWithdrawal::StatusPending,
                'destination' => $destination
            ]);

        } else if($nameAccount == 'NOT') {
            $destination = [
                'address' => $address,
                'memo' => $memo
            ];
            return AccountWithdrawal::create([
                'account_id' => $id,
                'name' => $nameAccount,
                'amount' => $amount,
                'user_id' => $user_id,
                'status' =>  AccountWithdrawal::StatusPending,
                'destination' => $destination
            ]);
        } else if($nameAccount == 'USDT') {
            $destination = [
                'address' => $address,
            ];
            return AccountWithdrawal::create([
                'account_id' => $id,
                'name' => $nameAccount,
                'amount' => $amount,
                'user_id' => $user_id,
                'status' =>  AccountWithdrawal::StatusPending,
                'destination' => $destination
            ]);
        }

        throw new \Exception('TON network withdrawals are not supported yet.');
    }

    public function showAccountWithdrawal(string $name, $user_id) {
        return AccountWithdrawal::query()
            ->where('name', $name)
            ->where('user_id', $user_id)
            ->orderBy('updated_at', 'desc');
    }

    /**
     * hủy lệnh rút khi đang lệnh đang chờ xử lý
     */
    public function cancelWithdrawal($id) {
        $accountWithdrawal = AccountWithdrawal::query()->find($id);
        if(!$accountWithdrawal) {
            throw new \Exception('id not found');
        }
        if($accountWithdrawal->status == AccountWithdrawal::StatusPending) {
            return $accountWithdrawal->delete();
        }

        throw new \Exception('Cannot cancel order');
    }

}
