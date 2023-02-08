<?php

namespace App\Model\Shop;

use App\Model\Model;

class Image extends Model
{
    public ?string $table = 'image';

    public array $visible = ['url'];

}