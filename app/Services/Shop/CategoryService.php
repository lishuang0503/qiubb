<?php

namespace App\Services\Shop;

use App\Model\Shop\Category;
use App\Model\Shop\Product;
use App\Services\BaseService;

class CategoryService extends BaseService
{
    public function list(): array
    {
        return Category::with([
            'img',
        ])->get()->toArray();
    }

    public function products(mixed $categoryId): array
    {
        return Product::where([
            'category_id'=>$categoryId
        ])->get()->toArray();
     }
}