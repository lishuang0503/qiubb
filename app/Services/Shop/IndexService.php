<?php

namespace App\Services\Shop;

use App\Model\Shop\Banner;
use App\Model\Shop\Category;
use App\Model\Shop\Product;
use App\Model\Shop\Theme;
use App\Services\BaseService;

class IndexService extends BaseService
{
    public function banner($bannerId): array
    {
        return Banner::query()->with([
            'items',
            'items.img',
        ])->find($bannerId)->toArray();
    }

    public function theme(mixed $themeIds): array
    {
        $themeIds = explode(',', $themeIds);
        return Theme::with([
            'topicImg',
            'headImg',
            'products',
        ])->whereIn('id', $themeIds)->get()->toArray();
    }

    public function recentProduct(): array
    {
        return Product::query()->limit(20)->orderByDesc('created_at')->get()->toArray();
    }
}