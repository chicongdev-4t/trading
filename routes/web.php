<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/trading/{code}', [\App\Http\Controllers\MarketOfferController::class, 'index'])->name('trading');

Route::put('/offers/{id}/buy', [\App\Http\Controllers\MarketOfferController::class, 'buy']);
Route::put('/offers/{id}/sell', [\App\Http\Controllers\MarketOfferController::class, 'sell']);

//Route::get('/orders/sell', [\App\Http\Controllers\MarketOfferController::class, 'orderSell']);
Route::post('/orders/sell', [\App\Http\Controllers\MarketOfferController::class, 'orderSell']);
Route::post('/orders/buy', [\App\Http\Controllers\MarketOfferController::class, 'orderBuy']);

route::get('/wallets', [\App\Http\Controllers\AccountController::class, 'indexWallet'])->name('wallets');
route::delete('/offers/{id}', [\App\Http\Controllers\MarketOfferController::class, 'deleteOffer']);

route::get('/withdraw', [\App\Http\Controllers\AccountWithdrawalController::class, 'createWithdrawal'])->name('withdraw');
Route::get('/withdraw/{name}/{balance}/{id}/{user_id}', function ($name, $balance, $id, $user_id) {
    return view('components.trading.withdraw', compact('name', 'balance', 'id', 'user_id'));
})->name('withdraw');

Route::post('/withdraw', [\App\Http\Controllers\AccountWithdrawalController::class, 'createWithdrawal']);
Route::get('/api/account-withdrawals/{name}/{user_id}', [\App\Http\Controllers\AccountWithdrawalController::class, 'showAccountWithdrawal']);
Route::get('/dashboard', [\App\Http\Controllers\MarketOfferController::class, 'listCoins'])->middleware(['auth', 'verified'])->name('dashboard');
require __DIR__.'/auth.php';
