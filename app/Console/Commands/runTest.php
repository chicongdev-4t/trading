<?php

namespace App\Console\Commands;

use App\Http\Controllers\MarketOfferController;
use App\Http\Controllers\ProfileController;
use App\Http\Service\AccountService;
use App\Http\Service\CurrencyService;
use App\Http\Service\MarketOfferService;
use App\Http\Service\UserService;
use App\Models\Currency;
use App\Models\MarketOffer;
use App\Models\User;
use Illuminate\Console\Command;

class runTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currencyService = new CurrencyService();
        $userService = new UserService();
        $accountService = new AccountService($currencyService);

        $user = User::query()->find(22);
        //$accountService->generateAccounts($user);

        $currency = Currency::query()->find(5);
        $marketOfferService = new MarketOfferService($userService, $currencyService, $accountService);
        //$marketOfferService->buyOrder($user, 2, $currency, 20000);

        //$userSell = User::query()->find(22);
        $marketOfferService->BuyOrder($user, 10, $currency, 1000);

        //$marketOfferService->SellOrder($user, 430, $currency, 1000);
        //$offer = MarketOffer::query()->find(7);
        //$marketOfferService->buy($user, 2, $offer);


    }
}
