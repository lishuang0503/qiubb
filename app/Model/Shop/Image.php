<?php

namespace App\Model\Shop;

use App\Model\Model;

class Image extends Model
{
    public ?string $table = 'image';

    public array $visible = ['url'];


    public function getUrlAttribute($url): string
    {
        return 'https://image-1255996407.cos.ap-shanghai.myqcloud.com/images'.$url;
    }

}