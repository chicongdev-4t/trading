<?php

class MarketOfferServiceTest extends \PHPUnit\Framework\TestCase
{
    public \App\Http\Service\MarketOfferService $service;

    public function __construct(\App\Http\Service\MarketOfferService $service)
    {
        $this->service = $service;
    }

    public function test_buy_order() {
        $user = \App\Models\User::query()->find(2);
        $amount = 10;
        $currency = \App\Models\Currency::query()->firstWhere('code', 'TON');
        $price = 100000;

        $this->service->buyOrder($user, $amount, $currency, $price);
    }

    public function sell_order_test() {

    }
}
