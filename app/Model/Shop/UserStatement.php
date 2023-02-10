<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class UserStatement extends Model
{
    public ?string $table = 'user_statement';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


}