<?php

namespace App\Model\Shop;

use App\Model\Model;
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property  string $name
 * @property  string $email
 */
class Supplier extends Model
{
    public ?string $table = 'supplier';
    public array $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

}