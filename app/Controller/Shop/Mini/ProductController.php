<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Services\Shop\ProductService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/product")]
class ProductController extends BaseController
{

    #[Inject]
    public ProductService $productService;
    public function detail(): ResponseInterface
    {
        [$productId] = $this->params('id');
        $data = $this->productService->detail($productId);
        return $this->success($data);
    }
}