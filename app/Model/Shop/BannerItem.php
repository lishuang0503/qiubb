<?php

namespace App\Model\Shop;
use App\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;

class BannerItem extends Model
{
    public ?string $table = 'banner_item';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function img(): BelongsTo
    {
        return $this->belongsTo(Image::class,'img_id','id');
    }

}