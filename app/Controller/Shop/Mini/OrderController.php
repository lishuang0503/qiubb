<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Model\Shop\Order;
use App\Services\Shop\OrderService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/order")]
class OrderController extends BaseController
{

    #[Inject]
    protected OrderService $orderService;

    public function generate(): ResponseInterface
    {
        [$oProduct, $remarks, $address, $receiveName, $receiveMobile] = $this->params('products', 'remarks', 'address', 'receive_name', 'receive_mobile');
      /*  $oProduct = [
            [
                'product_id' => '',
                'count' => 1,
            ],
            [
                'product_id' => '',
                'count' => 2,
            ],
        ];*/


        $remarks = [
            1 => '1号供应商备注',
            2 => '2号供应商备注',
            3 => '3号供应商备注',
            4 => '4号供应商备注',
            5 => '5号供应商备注',
            6 => '6号供应商备注',
            7 => '7号供应商备注',
        ];

        $data = $this->orderService->generate($oProduct, $remarks, $address, $receiveName, $receiveMobile);
        return $this->success($data);

    }


    //付款失败后再次支付
    public function pay(): ResponseInterface
    {
        $orderId = $this->params('id');
        $order = Order::find($orderId);
        $data = $this->orderService->preOrder($order->supplier_order_no, $order->total_price);
        return $this->success($data);
    }


    //订单详情
    public function detail(): ResponseInterface
    {
        [$orderId] = $this->params('id');
        $data = $this->orderService->detail($orderId);
        return $this->success($data);
    }


    //删除订单（软删除）
    public function delete(): ResponseInterface
    {
        [$orderId] = $this->params('id');
        $data = $this->orderService->delete($orderId);
        return $this->success($data);

    }

    //获取不同订单状态下的数量
    public function countsByStatus(): ResponseInterface
    {
        $data = $this->orderService->countsByStatus();
        return $this->success($data);
    }


    //订单列表
    public function list(): ResponseInterface
    {
        [$status,$currentPage,$pageSize] = $this->params('status','current_page','page_size');
        $data = $this->orderService->list($status,$currentPage,$pageSize);
        return $this->success($data);

    }
}