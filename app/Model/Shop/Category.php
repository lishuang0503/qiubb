<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;

class Category extends Model
{
    public ?string $table = 'category';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
        'topic_img_id',
    ];


    public function img(): BelongsTo
    {
        return $this->belongsTo(Image::class,'topic_img_id','id');
    }


}