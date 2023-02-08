<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\HasMany;

class OrderProduct extends Model
{
    public ?string $table = 'order_product';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}