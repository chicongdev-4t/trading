<?php

namespace App\Http\Service;

use App\Models\Account;
use App\Models\Currency;
use App\Models\User;

class AccountService
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /*
     * - muc dich tim account BTC cua user A
     */
    public function getAccount(User $user, Currency $currency)
    {
        return Account::query()->firstWhere([
            'user_id' => $user->id,
            'name' => $currency->code
        ]);
    }

    protected function store(User $user, Currency $currency) {
        return Account::query()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'name' => $currency->code,
            'balance' => 0,
        ]);
    }

    public function validateUserHaveAccount($accounts, Currency $currency) {
        // Kiem tra xem user co account nao r thi bo qua

        foreach($accounts as $account) {
            if($account->name == $currency->code) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vd: Tao tat ca account cho user A
     */
    public function generateAccounts(User $user)
    {
        /**
         * - Get tat ca currency
         * - Get tat ca account
         * - Kiem tra xem user co account nao r thi bo qua
         * - Neu chua co thi tao
         */

        // get tat ca currency
        $currencies = $this->currencyService->getAllCurrency();

        // get tat ca account
        $accounts = $user->accounts;

        // Kiem tra xem user co account nao r thi bo qua
           foreach($currencies as $currency) {
               if(!$this->validateUserHaveAccount($accounts, $currency)) {
                   // neu chua co thi tao
                   $this->store($user, $currency);
               }
           }
    }

    public function listCoinUserLoginUser() {
        $user = auth()->user();
        $accounts = $user->accounts;
        $imgBTC = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/e6bea4e0-a664-4332-a360-42799815de17.png';
        $imgETH = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/b492e3ab-0db5-4262-a4e7-8428931ad75b.png';
        $imgUSDC = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/1db3c13e-d402-464d-9221-02942c4bef60.png';
        $imgTON = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/750a52c3-9d75-4ae4-af20-0d6d57210c8a.png';
        $imgNOT = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/5466b1bc-db9c-469a-adc0-451c94d63222.png';


        $coins = [];
        foreach($accounts as $account) {
            if($account->name == 'BTC') {
                $coins[] = [
                    'id' => $account->id,
                    'user_id' => $account->user_id,
                    'name' => $account->name,
                    'img' => $imgBTC,
                    'balance' => $account->balance
                ];
            } else if($account->name == 'ETH') {
                $coins[] = [
                    'id' => $account->id,
                    'user_id' => $account->user_id,
                    'name' => $account->name,
                    'img' => $imgETH,
                    'balance' => $account->balance
                ];
            } else if($account->name == 'USDC') {
                $coins[] = [
                    'id' => $account->id,
                    'user_id' => $account->user_id,
                    'name' => $account->name,
                    'img' => $imgUSDC,
                    'balance' => $account->balance
                ];
            } else if($account->name == 'TON') {
                $coins[] = [
                    'id' => $account->id,
                    'user_id' => $account->user_id,
                    'name' => $account->name,
                    'img' =>  $imgTON,
                    'balance' => $account->balance
                ];
            } else if($account->name == 'NOT') {
                $coins[] = [
                    'id' => $account->id,
                    'user_id' => $account->user_id,
                    'name' => $account->name,
                    'img' =>  $imgNOT,
                    'balance' => $account->balance
                ];
            }
        }

        return $coins;
    }

    public function validateAmount(float $amount) {
        if($amount <= 0) {
            throw new \Exception('Invalid amount');
        }
    }

    public function validateAmountAndAmountAccount(float $amount, float $amountAccount) {
        if($amount > $amountAccount) {
            throw new \Exception('Amount greater than account balance, cannot be withdrawn');
        }
    }


}
