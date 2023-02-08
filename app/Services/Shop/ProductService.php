<?php

namespace App\Services\Shop;

use App\Model\Shop\Product;
use App\Services\BaseService;

class ProductService extends BaseService
{

    public function detail(mixed $productId): array
    {
        return Product::with([
            'imgs',
            'properties',
        ])->find($productId)->toArray();
    }
}