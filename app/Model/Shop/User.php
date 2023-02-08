<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property  string $openid
 */
class User extends Model
{
    public ?string $table = 'user';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

}