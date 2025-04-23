<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountChange extends Model
{
    use HasFactory;
    const TypeSell = 'Sell';
    const TypeBuy = 'Buy';

    const TypeCreate = 'create_offer';

    const TypeCancel = 'cancel_offer';

    const TypeDeposit = 'deposit';

    const TypeWithdraw = 'withdraw';

    const TypeReceive = 'receive';

    const TypeSend = 'send';

    const Change = 0;

    const TypeFee = 'fee';
    protected $fillable = ['account_id', 'currency', 'type', 'before_balance', 'change', 'after_balance', 'note'];

    public function account() {
        return $this->belongsTo(Account::class);
    }
    public function getImgAttribute()
    {
        $currencyCode = $this->currency?? '';

        return match ($currencyCode) {
            'BTC' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/e6bea4e0-a664-4332-a360-42799815de17.png',
            'ETH' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/b492e3ab-0db5-4262-a4e7-8428931ad75b.png',
            'USDC' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/1db3c13e-d402-464d-9221-02942c4bef60.png',
            'TON' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/750a52c3-9d75-4ae4-af20-0d6d57210c8a.png',
            'NOT' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/5466b1bc-db9c-469a-adc0-451c94d63222.png',
            'USDT' => 'https://aliniex.sgp1.digitaloceanspaces.com/alixgate/logo/cea2fa38-61ae-4fd9-b229-fbe622f3dcdd.png',
            default => '', // Hoặc một hình ảnh mặc định nếu cần
        };
    }
}
