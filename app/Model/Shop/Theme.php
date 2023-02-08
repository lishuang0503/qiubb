<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\BelongsToMany;

class Theme extends Model
{
    public ?string $table = 'theme';

    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'head_img_id',
        'topic_img_id',
    ];

    public function topicImg(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'topic_img_id', 'id');
    }

    public function headImg(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'head_img_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'theme_product','theme_id','product_id');
    }

}