<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\HasMany;

class Order extends Model
{
    public ?string $table = 'order';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BannerItem::class, 'banner_id', 'id');
    }
}