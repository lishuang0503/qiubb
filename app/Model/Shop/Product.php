<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;


/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property  int $stock
 */
class Product extends Model
{
    public ?string $table = 'product';


    const STATUS_UP = 1;//上架
    const STATUS_DOWN = 2;//下架

    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'pivot',
        'from',
        'category_id'
    ];

    public function getMainImgUrlAttribute($url): string
    {
        return 'https://image-1255996407.cos.ap-shanghai.myqcloud.com/images' . $url;
    }

    public function mainImgs(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_image', 'product_id', 'img_id')->wherePivot('type', '=','main');
    }

    public function detailImgs(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_image', 'product_id', 'img_id')->wherePivot('type', '=','detail');
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }


    public function properties(): HasMany
    {
        return $this->hasMany(ProductProperty::class, 'product_id', 'id');
    }

    //上架作用域
    public function scopeStatus($query, $status = self::STATUS_UP)
    {
        return $query->where([
            'status' => $status,
        ]);
    }

    //指定商品id作用域
    public function scopeProductIds($query, $productId)
    {
        return $query->when(is_array($productId), function ($query) use ($productId) {
            return $query->whereIn('id', $productId);
        }, function ($query) use ($productId) {
            return $query->where('id', $productId);
        });
    }
}