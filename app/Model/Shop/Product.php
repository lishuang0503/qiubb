<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

class Product extends Model
{
    public ?string $table = 'product';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'pivot',
        'from',
        'category_id'
    ];


    public function imgs(): BelongsToMany
    {
        return $this->belongsToMany(Image::class,'product_image','product_id','img_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(ProductProperty::class,'product_id','id');
    }
}