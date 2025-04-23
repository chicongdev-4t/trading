<?php

namespace App\Http\Controllers;

use App\Http\Service\AccountChangeService;
use App\Http\Service\MarketOfferService;
use App\Models\Currency;
use App\Models\MarketOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;




class MarketOfferController extends Controller
{
    protected MarketOfferService $offerService;
    protected AccountChangeService $accountChangeService;


    public function __construct(MarketOfferService $offerService, AccountChangeService $accountChangeService) {
        $this->offerService = $offerService;
        $this->accountChangeService = $accountChangeService;
    }

    /*
     * - index: hien thi ds
     * - create: hien thi form tao data
     * - store: tao data
     * - show: hien thi 1 data
     * - edit: hien thi form chinh sua data
     * - update: cap nhat data
     * - delete: xoa 1 data
     */

    public function listCoins() {
        $coins = $this->offerService->listCoins();
        return view('dashboard', compact('coins'));
    }


    public function index($code)
    {


        $currency = Currency::query()->where('code', $code)->first();
        $currencyId = $currency->id;

        $sellPage = request()->query('sell_page', 1);
        $offersSell = MarketOffer::query()
            ->where('type', MarketOffer::TypeSell)
            ->where('currency_id', $currencyId)
            ->where('status', '!=', MarketOffer::StatusCompleted)
            ->orderBy('price', 'asc')
            ->paginate(10, ['*'], 'sell_page', $sellPage);

        $buyPage = request()->query('buy_page', 1);
        $offersBuy = MarketOffer::query()
            ->where('type', MarketOffer::TypeBuy)
            ->where('status', '!=', MarketOffer::StatusCompleted)
            ->where('currency_id', $currencyId)
            ->orderBy('price', 'desc')
            ->paginate(10, ['*'], 'buy_page', $buyPage);




        // lay $offersPendingOneUserLogin
        $pendingPage = request()->query('pending_page', 1);
        $offerPendings = $this->offerService->getAllofferPendingOneUserLogin()->paginate(10, ['*'], 'pending_page', $pendingPage);

        // lay lich su bien dong so du cua user login trong account
        $transactionPage = request()->query('transaction_page', 1);
        $transactions = $this->accountChangeService->getAll()->paginate(10, ['*'], 'transaction_page', $transactionPage);


        // Bảo toàn tất cả tham số khi phân trang
        $offersSell->appends([
            'buy_page' => $buyPage,
            'pending_page' => $pendingPage,
            'transaction_page' => $transactionPage,
        ]);

        $offersBuy->appends([
            'sell_page' => $sellPage,
            'pending_page' => $pendingPage,
            'transaction_page' => $transactionPage,
        ]);

        $offerPendings->appends([
            'sell_page' => $sellPage,
            'buy_page' => $buyPage,
            'transaction_page' => $transactionPage,
        ]);

        $transactions->appends([
            'sell_page' => $sellPage,
            'buy_page' => $buyPage,
            'pending_page' => $pendingPage,
        ]);

        return view('components.trading.index', compact('offersSell', 'offersBuy', 'code', 'offerPendings', 'transactions'));
    }

    public function deleteOffer($id) {
        return $this->offerService->deleteOffer($id);
    }


    public function buy($id)
    {
        $buyer = auth()->user();
        $userId = $buyer->id;

        \request()->validate([
            'amount' => 'required',
        ]);

        $amount = request('amount');
        $offer = MarketOffer::query()->find($id);

        return $this->offerService->buy($buyer, $amount, $offer);

    }

    public function sell($id)
    {
        $seller = auth()->user();
        $userId = $seller->id;

        \request()->validate([
            'amount' => 'required',
        ]);

        $amount = request('amount');
        $offer = MarketOffer::query()->find($id);

        return $this->offerService->sell($seller, $amount, $offer);
    }

    public function orderSell() {
        \request()->validate([
            'codeSeller' => 'required',
            'amountSeller' => 'required',
            'priceSeller' => 'required',
        ]);

        $user = auth()->user();
        $code = request('codeSeller');
        $amount = request('amountSeller');
        $currency = Currency::query()->where('code', $code)->first();
        $price = request('priceSeller');

        return $this->offerService->sellOrder($user, $amount, $currency, $price);
    }

    public function orderBuy() {
        \request()->validate([
            'codeBuyer' => 'required',
            'amountBuyer' => 'required',
            'priceBuyer' => 'required',
        ]);

        $user = auth()->user();
        $code = request('codeBuyer');
        $amount = request('amountBuyer');
        $currency = Currency::query()->where('code', $code)->first();
        $price = request('priceBuyer');

        return $this->offerService->buyOrder($user, $amount, $currency, $price);
    }
}
