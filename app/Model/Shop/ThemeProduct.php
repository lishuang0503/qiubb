<?php

namespace App\Model\Shop;

use App\Model\Model;
use Hyperf\Database\Model\Relations\HasMany;

class ThemeProduct extends Model
{
    public ?string $table = 'theme_product';

}