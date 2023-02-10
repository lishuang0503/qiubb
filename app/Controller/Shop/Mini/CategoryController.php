<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Services\Shop\CategoryService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/category")]
 class CategoryController extends BaseController
{

    #[Inject]
    protected CategoryService $categoryService;

    public function list(): ResponseInterface
    {
        $data = $this->categoryService->list();
        return $this->success($data);
    }

    public function product(): ResponseInterface
    {
        [$categoryId] = $this->params('id');
        $data = $this->categoryService->products($categoryId);
        return $this->success($data);
    }
}