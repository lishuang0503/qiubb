<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Services\Shop\IndexService;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/index")]
 class IndexController extends BaseController
{
    public function banner(IndexService $indexService): ResponseInterface
    {
        [$bannerId] = $this->params('banner_id');
        $data = $indexService->banner($bannerId);
        return $this->success($data);
    }

    public function theme(IndexService $indexService): ResponseInterface
    {
        [$themeIds] = $this->params('theme_ids');
        $data = $indexService->theme($themeIds);
        return $this->success($data);
    }

    public function recentProduct(IndexService $indexService): ResponseInterface
    {
        $data = $indexService->recentProduct();
        return $this->success($data);
    }


    public function viewProduct(IndexService $indexService): ResponseInterface
    {
        [$themeId,$currentPage,$pageSize] = $this->params('theme_id','current_page','page_size');
        $data = $indexService->viewProduct($themeId,$currentPage,$pageSize);
        return $this->success($data);
    }
}