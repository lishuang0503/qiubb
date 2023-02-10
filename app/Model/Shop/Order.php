<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property  string $supplier_order_no
 * @property  string $total_price
 */
class Order extends Model
{

    //订单状态 1：待支付 2：已支付,待发货 3：已发货，待收货 4：已收货，待评价 20：超时关闭
    const ORDER_WAIT_PAY = 1;
    const ORDER_WAIT_DELIVERY = 2;
    const ORDER_WAIT_RECEIVE = 3;
    const ORDER_WAIT_ASSESS = 4;
    const ORDER_CLOSE = 20;


    public ?string $table = 'order';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public array $casts = [
        'snap_items' => 'array'
    ];

    public static function getStatusLabels(): array
    {
        return [
            self::ORDER_WAIT_PAY => '待支付',
            self::ORDER_WAIT_DELIVERY => '待发货',
            self::ORDER_WAIT_RECEIVE => '待收货',
            self::ORDER_WAIT_ASSESS => '待评价',
            self::ORDER_CLOSE => '超时关闭',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(BannerItem::class, 'banner_id', 'id');
    }
}