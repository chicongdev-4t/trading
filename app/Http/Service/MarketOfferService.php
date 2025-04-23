<?php

namespace App\Http\Service;

use App\Models\AccountChange;
use App\Models\Currency;
use App\Models\MarketOffer;
use App\Models\MarketTrade;
use App\Models\User;

class MarketOfferService
{
    protected UserService $userService;
    protected CurrencyService $currencyService;
    protected AccountService $accountService;

    public function __construct(UserService $userService, CurrencyService $currencyService, AccountService $accountService)
    {
        $this->userService = $userService;
        $this->currencyService = $currencyService;
        $this->accountService = $accountService;
    }

    /**
     * lấy ra danh sách các coin có giá bán thấp nhất
     * lấy ra danh sách các coin có giá mua cao nhất
     */
    public function listCoins() {
        /**
         * - duyệt lần lượt từng đồng coin
         * - mỗi đồng lấy ra giá bán thấp nhất, giá mua cao nhất rồi lưu mảng, lưu cả ảnh nữa
         */

        $imgBTC = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/e6bea4e0-a664-4332-a360-42799815de17.png';
        $imgETH = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/b492e3ab-0db5-4262-a4e7-8428931ad75b.png';
        $imgUSDC = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/1db3c13e-d402-464d-9221-02942c4bef60.png';
        $imgTON = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/750a52c3-9d75-4ae4-af20-0d6d57210c8a.png';
        $imgNOT = 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/5466b1bc-db9c-469a-adc0-451c94d63222.png';

        // duyet lan luot tung dong coin
        $currencies = Currency::query()->get();

        // mỗi đồng lấy ra giá bán thấp nhất, giá mua cao nhất rồi lưu mảng, lưu cả ảnh nữa
        $coins = [];
        foreach($currencies as $currency) {
            $offerSell = MarketOffer::query()->where('currency_id', $currency->id)
                                             ->where('type', 'Sell')
                                             ->where('status', 'Pending')
                                             ->orderBy('price', 'asc')
                                             ->first();
            $offerBuy = MarketOffer::query()->where('currency_id', $currency->id)
                                            ->where('type', 'Buy')
                                            ->where('status', 'Pending')
                                            ->orderBy('price', 'desc')
                                            ->first();
            if($currency->code == 'BTC') {
                $coins[] = [
                    'name' => $currency->code,
                    'priceBuy' => $offerBuy ? $offerBuy->price : 'N/A',
                    'priceSell' => $offerSell ? $offerSell->price : 'N/A',
                    'img' => $imgBTC
                ];
            } else if($currency->code == 'ETH') {
                $coins[] = [
                    'name' => $currency->code,
                    'priceBuy' => $offerBuy ? $offerBuy->price : 'N/A',
                    'priceSell' => $offerSell ? $offerSell->price : 'N/A',
                    'img' => $imgETH
                ];
            } else if($currency->code == 'USDC') {
                $coins[] = [
                    'name' => $currency->code,
                    'priceBuy' => $offerBuy ? $offerBuy->price : 'N/A',
                    'priceSell' => $offerSell ? $offerSell->price : 'N/A',
                    'img' => $imgUSDC
                ];
            } else if($currency->code == 'TON') {
                $coins[] = [
                    'name' => $currency->code,
                    'priceBuy' => $offerBuy ? $offerBuy->price : 'N/A',
                    'priceSell' => $offerSell ? $offerSell->price : 'N/A',
                    'img' => $imgTON
                ];
            } else if($currency->code == 'NOT') {
                $coins[] = [
                    'name' => $currency->code,
                    'priceBuy' => $offerBuy ? $offerBuy->price : 'N/A',
                    'priceSell' => $offerSell ? $offerSell->price : 'N/A',
                    'img' => $imgNOT
                ];
            }
        }
        return $coins;
    }

    public function totalUSDTPending($userId) {
        $marketOffers = MarketOffer::query()->where('user_id', $userId)
                                            ->where('status', MarketOffer::StatusPending)
                                            ->where('type', MarketOffer::TypeBuy)->get();
        $total = 0;
        foreach($marketOffers as $marketOffer) {
            $total = $total + $marketOffer->available_amount * $marketOffer->price;
        }

        return $total;
    }

    /**
     * Vd: User A mua 10 BTC voi gia 100.000
     */
    public function buyOrder(User $user, float $amount, Currency $currency, float $price)
    {
        /**
         * - Kt xem user A co account BTC hay k
         * - Kt xem user A co account USDT hay k
         * - Kt xem user co du USDT de mua hay k
         * - Tao lenh mua, tạo thêm ảnh
         */

        $account = $this->accountService->getAccount($user, $currency);

        if (!$account) {
            throw new \Exception('Account not found');
        }

        $usdt = $this->currencyService->getByCode('USDT');
        $usdtAccount = $this->accountService->getAccount($user, $usdt);

        if (!$usdtAccount) {
            throw new \Exception('Account not found');
        }
        // - Kt xem user co du USDT de mua hay k
           // tính tổng USDT  các lệnh đang chờ có type = 'buy', có cùng user_id, currency_id, status = 'Pending'

        $totalUSDTPending = $this->totalUSDTPending($user->id);

        $totalUSDTPending = $totalUSDTPending + (0.1/100) * $totalUSDTPending;

        $totalUSDT = $usdtAccount->balance - $totalUSDTPending;

        //dd($totalUSDT);
        if(($amount * $price) + (0.1/100) * ($amount * $price) > $totalUSDT) {
            throw new \Exception('Không đủ USDT để đặt lệnh mua');
        }

//        if ($usdtAccount->balance < $total) {
//            throw new \Exception('Invalid amount');
//        }

        // tao lenh mua
         MarketOffer::create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'type' => MarketOffer::TypeBuy,
            'amount' => $amount,
            'available_amount' => $amount,
            'price' => $price
        ]);


    }

    /**
     * lay tat ca cac lenh mua ban cua nguoi dang nhap
     */
    public function getAllofferPendingOneUserLogin() {
        $user = auth()->user();
        $userId = $user->id;

        $offers = MarketOffer::query()->where('user_id', $userId)
                                      ->where('status', 'Pending')->orderBy('updated_at', 'desc');
        return $offers;
    }

    public function deleteOffer($id) {
        $offer = MarketOffer::query()->find($id);

        // kiểm tra xem amount đã có ai mua chưa, nếu đã có người mua thì không thể xóa
        if($offer->amount != $offer->available_amount) {
            throw new \Exception('This order has been partially traded and cannot be cancelled.');
        }

        if (!$offer) {
            throw new \Exception('order not found, no delete');
        }
        $offer->delete();
    }




    /**
     * User A mua 5 BTC cua User B
     */
    public function buy(User $buyer, float $amount, MarketOffer $offer)
    {
        /**
         *  - kiem tra xem userBuy da co account BTC chua
         *  - kiem tra xem userBuy da co account USDT chua
         *  - kiem tra xem USDT cua userBuy co du de mua khong
         *  - kiem tra xem quantity > 0
         *  - kiem tra xem quantity <= amount cua marketOffer khong
         *  - kiem tra xem marketOffer co type la sell khong
         *  - mua
         * -  luu vao account change
         */

        // kiem tra xem $buyer da co account BTC chua
        $currency =  $offer->currency;

        $accountBuyer = $this->accountService->getAccount($buyer, $currency);

        if (!$accountBuyer) {
            throw new \Exception('Account not found');
        }

        // kiem tra xem userBuy da co account USDT chua
        $usdt = $this->currencyService->getByCode('USDT');
        $usdtAccountBuyer = $this->accountService->getAccount($buyer, $usdt);

        if (!$usdtAccountBuyer) {
            throw new \Exception('Account  not found');
        }

        // kiem tra xem marketOffer co type la sell khong
        if($offer->type !== MarketOffer::TypeSell) {
            throw new \Exception('Invalid offer');
        }

        // kiem tra xem quantity > 0
        if ($amount <= 0 || $amount > $offer->available_amount) {
            throw new \Exception('Invalid amount');
        }

        // - kiem tra xem USDT cua userBuy co du de mua khong

        // tong gia kem theo phi giao dich
        $subTotal = $offer->price * $amount;
        $transactionFee = $subTotal * 0.1/100;
        $totalUSDTSend = $subTotal + $transactionFee;
        $totalUSDTReceive = $subTotal - $transactionFee;
        if($totalUSDTSend > $usdtAccountBuyer->balance) {
            throw new \Exception('USDT is not enough to buy');
        }


        //update account userBuy
        $balanceBeforeAccountBuyer = $accountBuyer->balance;
        $accountBuyer->balance = $accountBuyer->balance + $amount;
        $accountBuyer->save();

        $balanceBeforeUsdtAccountBuyer = $usdtAccountBuyer->balance;
        $usdtAccountBuyer->balance = $usdtAccountBuyer->balance - $totalUSDTSend;
        $usdtAccountBuyer->save();

        // update account userSell
        $seller = $offer->user;
        $accountSeller = $this->accountService->getAccount($seller, $currency);
        $balanceBeforeAccountSeller = $accountSeller->balance;
        $accountSeller->balance = $accountSeller->balance - $amount;
        $accountSeller->save();

        $usdtAccountSeller = $this->accountService->getAccount($seller, $usdt);
        $balanceBeforeUsdtAccountSeller = $usdtAccountSeller->balance;
        $usdtAccountSeller->balance = $usdtAccountSeller->balance + $totalUSDTReceive;
        $usdtAccountSeller->save();

        // update marketOffer
        $offer->available_amount = $offer->available_amount - $amount;
        if($offer->available_amount == 0) {
            $offer->status = MarketOffer::StatusCompleted;
        }
        $offer->save();

        // luu vao market_trades
         MarketTrade::create([
             'user_id' => $buyer->id,
             'offer_id' => $offer->id,
             'type' => MarketOffer::TypeBuy,
             'amount' => $amount,
             'currency' => $currency->code,
             'price' => $offer->price,
             'sub_total' => $subTotal,
             'total' => $totalUSDTSend
         ]);

         MarketTrade::create([
            'user_id' => $seller->id,
            'offer_id' => $offer->id,
            'type' => MarketOffer::TypeSell,
             'amount' => $amount,
             'currency' => $currency->code,
             'price' => $offer->price,
             'sub_total' => $subTotal,
             'total' => $totalUSDTReceive,
         ]);

         // luu vao account change

         // luu buyer vào account change
         AccountChange::create([
             'account_id' => $accountBuyer->id,
             'currency' => $currency->code,
             'type' => AccountChange::TypeBuy,
             'before_balance' => $balanceBeforeAccountBuyer,
             'change' => "+".$amount,
             'after_balance'=> $accountBuyer->balance,
             'note' => 'offerId_'.$offer->id

         ]);

        AccountChange::create([
            'account_id' => $usdtAccountBuyer->id,
            'currency' => 'USDT',
            'type' => AccountChange::TypeSend,
            'before_balance' => $balanceBeforeUsdtAccountBuyer,
            'change' => $totalUSDTSend,
            'after_balance'=> $usdtAccountBuyer->balance,
            'note' => 'offerId_'.$offer->id

        ]);



        // luu seller vào account change
        AccountChange::create([
            'account_id' => $accountSeller->id,
            'currency' => $currency->code,
            'type' => AccountChange::TypeSell,
            'before_balance' => $balanceBeforeAccountSeller,
            'change' => $amount,
            'after_balance'=> $accountSeller->balance,
            'note' => 'offerId_'.$offer->id
        ]);

        AccountChange::create([
            'account_id' => $usdtAccountSeller->id,
            'currency' => 'USDT',
            'type' => AccountChange::TypeReceive,
            'before_balance' => $balanceBeforeUsdtAccountSeller,
            'change' => $totalUSDTReceive,
            'after_balance'=> $usdtAccountSeller->balance,
            'note' => 'offerId_'.$offer->id
        ]);


    }

    /**
     * User A bán 5 BTC cho User B
     */
    public function sell(User $seller, float $amount, MarketOffer $offer)
    {
        /**
         * - kiem tra xem seller da co account BTC chua
         * - kiem tra xem seller da co account USDT chua
         * - kiem tra xem amount > 0
         *  - kiem tra xem amount <= amount cua marketOffer khong
         * - kiem tra xem marketOffer co type la buy khong
         * - kiem tra xem BTC cua seller co du de ban khong
         * - ban
         * - luu vao account change
         */

        // kiem tra xem seller da co account BTC chua
        $currency =  $offer->currency;

        $accountSeller = $this->accountService->getAccount($seller, $currency);

        if (!$accountSeller) {
            throw new \Exception('Account not found');
        }

        // kiem tra xem seller da co account USDT chua
        $usdt = $this->currencyService->getByCode('USDT');
        $usdtAccountSeller = $this->accountService->getAccount($seller, $usdt);

        if (!$usdtAccountSeller) {
            throw new \Exception('Account  not found');
        }

        // kiem tra xem marketOffer co type la buy khong
        if($offer->type !== MarketOffer::TypeBuy) {
            throw new \Exception('Invalid offer');
        }

        // kiem tra xem quantity > 0
        if ($amount <= 0 || $amount > $offer->available_amount) {
            throw new \Exception('Invalid amount');
        }

        // - kiem tra xem BTC cua seller co du de ban khong
        if($amount > $accountSeller->balance) {
            throw new \Exception('Account balance is insufficient to sell ');
        }

        // bán

        //update account userSell
        $beforeAccountSerller = $accountSeller->balance;
        $accountSeller->balance = $accountSeller->balance - $amount;
        $accountSeller->save();


        // tong gia kem theo phi giao dich
        $subTotal = $offer->price * $amount;
        $transactionFee = $subTotal * 0.1/100;
        $totalUSDTSend = $subTotal + $transactionFee;
        $totalUSDTReceive = $subTotal - $transactionFee;

        $beforeUsdtAccountSeller = $usdtAccountSeller->balance;
        $usdtAccountSeller->balance = $usdtAccountSeller->balance + $totalUSDTReceive;
        $usdtAccountSeller->save();

        // update account userBuy
        $buyer = $offer->user;
        $accountBuyer = $this->accountService->getAccount($buyer, $currency);
        $beforeAccountBuyer = $accountBuyer->balance;
        $accountBuyer->balance = $accountBuyer->balance + $amount;
        $accountBuyer->save();

        $usdtAccountBuyer = $this->accountService->getAccount($buyer, $usdt);
        $beforeUsdtAccountBuyer = $usdtAccountBuyer->balance;
        $usdtAccountBuyer->balance = $usdtAccountBuyer->balance - $totalUSDTSend;
        $usdtAccountBuyer->save();

        // update marketOffer
        $offer->available_amount = $offer->available_amount - $amount;
        if($offer->available_amount == 0) {
            $offer->status = MarketOffer::StatusCompleted;
        }
        $offer->save();

        // luu vao market_trades

        MarketTrade::create([
            'user_id' => $seller->id,
            'offer_id' => $offer->id,
            'type' => MarketOffer::TypeSell,
            'amount' => $amount,
            'currency' => $currency->code,
            'price' => $offer->price,
            'sub_total' => $subTotal,
            'total' => $totalUSDTReceive
        ]);

        MarketTrade::create([
            'user_id' => $buyer->id,
            'offer_id' => $offer->id,
            'type' => MarketOffer::TypeBuy,
            'amount' => $amount,
            'currency' => $currency->code,
            'price' => $offer->price,
            'sub_total' => $subTotal,
            'total' => $totalUSDTSend
        ]);


        // luu vao account change

        // luu seller vào account change
        AccountChange::create([
            'account_id' => $accountSeller->id,
            'currency' => $currency->code,
            'type' => AccountChange::TypeSell,
            'before_balance' => $beforeAccountSerller,
            'change' => $amount,
            'after_balance'=> $accountSeller->balance,
            'note' => 'offerId_'.$offer->id
        ]);

        AccountChange::create([
            'account_id' => $usdtAccountSeller->id,
            'currency' => 'USDT',
            'type' => AccountChange::TypeReceive,
            'before_balance' => $beforeUsdtAccountSeller,
            'change' => $totalUSDTReceive,
            'after_balance'=> $usdtAccountSeller->balance,
            'note' => 'offerId_'.$offer->id
        ]);


        // luu buyer vào account change
        AccountChange::create([
            'account_id' => $accountBuyer->id,
            'currency' => $currency->code,
            'type' => AccountChange::TypeBuy,
            'before_balance' => $beforeAccountBuyer,
            'change' => $amount,
            'after_balance'=> $accountBuyer->balance,
            'note' => 'offerId_'.$offer->id
        ]);

        AccountChange::create([
            'account_id' => $usdtAccountBuyer->id,
            'currency' => 'USDT',
            'type' => AccountChange::TypeSend,
            'before_balance' => $beforeUsdtAccountBuyer,
            'change' => $totalUSDTSend,
            'after_balance'=> $usdtAccountBuyer->balance,
            'note' => 'offerId_'.$offer->id
        ]);
    }

    public function getTotalAvailableAmount($userId, $currencyId) {
        $marketOffers = MarketOffer::query()->where('user_id', $userId)
                                           ->where('currency_id', $currencyId)
                                           ->where('status', 'Pending')->get();

        $total = 0;
        foreach($marketOffers as $marketOffer) {
            $total = $total + $marketOffer->available_amount;
        }

        return $total;
    }
    /**
     * Vd: User A ban 10 btc voi gia 100.000
     */
    public function sellOrder(User $user, float $amount, Currency $currency, float $price)
    {
        /**
         * - Kt xem user co account hay k
         * - Kt xem user co du so du hay k
         * - Tao lenh ban
         */

        $account = $this->accountService->getAccount($user, $currency);
        $total = $account->balance;

        // - Kt xem user co account hay k
        if (!$account) {
            throw new \Exception('Account not found');
        }

//        if ($amount > $account->balance) {
//            throw new \Exception('Invalid amount');
//        }
        // - Kt xem user co du so du hay k
           // - b1: tổng hợp số lượng các lệnh đang chờ có trang thái là Pending
         $totalAmountPending = $this->getTotalAvailableAmount($user->id, $currency->id);
        $totalAvailable = $total - $totalAmountPending;
        if($amount > $totalAvailable) {
            throw new \Exception('Invalid amount');
        }



        MarketOffer::query()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'type' => MarketOffer::TypeSell,
            'amount' => $amount,
            'available_amount' => $amount,
            'price' => $price
        ]);

    }
}
