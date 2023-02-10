<?php

namespace App\Services\Shop;

use App\Model\Shop\Product;
use App\Services\BaseService;
use Exception;
use Hyperf\Database\Model\Collection;

class ProductService extends BaseService
{

    public function detail(mixed $productId): array
    {
        $data = Product::with([
            'mainImgs',
            'detailImgs',
            'properties',
            'supplier:id,name'
        ])->status(Product::STATUS_UP)->find($productId)?->toArray();
        if (!$data['main_imgs']) {
            $data['main_imgs'][] = [
                'url' => $data['main_img_url']
            ];
        }
        return $data;
    }

    /**
     * @throws Exception
     */
    public function checkProductExistsAndStatus(array $productIds): Collection|array|\Hyperf\Utils\Collection
    {
        $products = $this->getProductByIds($productIds);
        $diff =  array_diff($productIds,array_column($products->toArray(),'id'));
        if ($diff) throw new Exception('id=' . implode(',', $diff) . '商品不存在或已下架');
        return $products;
    }


    public function getProductByIds($productIds): Collection|array|\Hyperf\Utils\Collection
    {
        return Product::with(['supplier'])->where([
            'status'=>Product::STATUS_UP,
        ])->whereIn('id',$productIds)->get();
    }


    /**
     * @throws Exception
     */
    public function checkProductStock($product, $count): bool
    {

        if ($product instanceof Product) {
            $productId = $product->id;
            $stock = $product->stock;
        } elseif (is_array($product)) {
            $productId = $product['id'];
            $stock = $product['stock'];
        } else {
            throw new Exception('检查商品库存传参格式不正确');
        }
        //检查商品库存
        if ($count > $stock) {
            throw new Exception('id=' . $productId . '商品库存不足');
        }
        return true;
    }
}