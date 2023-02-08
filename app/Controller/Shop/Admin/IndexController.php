<?php

namespace App\Controller\Shop\Admin;

use App\Controller\Shop\BaseController;
use App\Services\Shop\IndexService;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shop/admin")]
class IndexController extends BaseController
{
    public function getBanner(IndexService $indexService): ResponseInterface
    {
        [$bannerId] = $this->params('banner_id');
        $data = $indexService->getBanner($bannerId);
        return $this->success($data);
    }
}