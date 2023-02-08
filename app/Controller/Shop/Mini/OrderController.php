<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Services\Shop\OrderService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/order")]
class OrderController extends BaseController
{

    #[Inject]
    protected OrderService $orderService;

    public function preOrder(): ResponseInterface
    {
        [$params] = $this->params('params');
        $data = $this->orderService->preOrder($params);
        return $this->success($data);

    }


    public function pay()
    {

    }
}