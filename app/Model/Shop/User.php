<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\HasMany;

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

    //用户订单信息
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'user_id','id');
    }

}