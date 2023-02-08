<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\HasMany;

class ProductProperty extends Model
{
    public ?string $table = 'product_property';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}