<?php

namespace App\Services\Shop;

use App\Model\Shop\Order;
use App\Model\Shop\Supplier;
use App\Model\Shop\User;
use App\Services\BaseService;
use Exception;
use HPlus\Helper\DbHelper\GetQueryHelper;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Yansongda\HyperfPay\Pay;
use Yansongda\Supports\Collection;

class OrderService extends BaseService
{
    use GetQueryHelper;

    #[Inject]
    public ProductService $productService;

    #[Inject]
    public Pay $pay;

    public function generate($oProduct, $remarks, $address, $receiveName, $receiveMobile)
    {
        $oProductIdCounts = array_column($oProduct, 'count', 'product_id');
        return Db::transaction(function () use ($oProductIdCounts, $address, $receiveName, $receiveMobile) {
            $data = $this->create($oProductIdCounts, $address, $receiveName, $receiveMobile);
            return $this->preOrder($data['orderNo'], $data['totalPrice']);
        });
    }

    /**
     * @throws Exception
     */
    public function create($oProductIdCounts, $address, $receiveName, $receiveMobile): array
    {
        //再次验证商品数据
        $products = $this->productService->checkProductExistsAndStatus(array_keys($oProductIdCounts));
        // p($products);
        //验证商品库存 并生成商品需要的数据
        $orderNo = generate_order_no('O');
        $orders = $products->map(function ($item) use ($oProductIdCounts) {
            $count = $oProductIdCounts[$item->id] ?? 0;
            if ($this->productService->checkProductStock($item, $count)) {
                $item->count = $count;
                $item->sum_price = bcmul($count, $item->price, 2);
            }
            return $item;
        })->groupBy([
            'supplier_id',
        ])->map(function ($item, $supplierId) use ($orderNo, $address, $receiveName, $receiveMobile) {
            $supplierName = Supplier::find($supplierId)->name;
            return [
                'supplier_id' => $supplierId,
                'supplier_order_no' => $orderNo . '-S-' . $supplierId,
                'order_no' => $orderNo,
                'total_count' => $item->sum('count'),
                'total_price' => $item->sum('sum_price'),
                'snap_items' => $item,
                'transit_price' => 0,
                'snap_address' => $address,
                'snap_receive_name' => $receiveName,
                'snap_receive_mobile' => $receiveMobile,
                'supplier_name' => $supplierName,
                'remark' => '',
            ];
        });
        User::find(auth()['id'])?->orders()->createMany($orders->toArray());
        $totalPrice = $orders->sum('total_price');
        return compact('orderNo', 'totalPrice');
    }


    public function preOrder($orderNo, $totalPrice, $body = ''): Collection
    {
        $data = $this->pay->wechat()->mini([
            'out_trade_no' => $orderNo,
            'description' => $body ?: 'subject-测试',
            'amount' => [
                'total' => $totalPrice * 100,
                'currency' => 'CNY',
            ],
            'payer' => [
                'openid' => auth()['openid'],
            ],
        ]);
        [$_, $prepayId] = explode('=', $data['package']);
        $this->updatePrepayId($orderNo, $prepayId);
        return $data;
    }

    public function detail(int $orderId): ?array
    {
        return Order::where([
            'user_id' => auth()['id'],
            'id' => $orderId,
        ])->select([
            'id',
            'order_no',
            'supplier_order_no',
            'status',
            'address as snap_address',
            'receive_name as snap_receive_name',
            'receive_mobile as snap_receive_mobile',
            'supplier_name',
            'supplier_id',
            'items as snap_items',
            'transit_price',
            'total_count',
            'total_price',
            'created_at',
        ])->first()?->toArray();
    }

    public function delete(mixed $orderId)
    {
        return Order::where([
            'user_id' => auth()['id'],
            'id' => $orderId
        ])->first()?->delete();
    }

    public function countsByStatus(): array
    {
        return Order::where([
            'user_id' => auth()['id'],
        ])->select([
            'status',
            DB::raw('COUNT(id) as num'),
        ])->groupBy(['status'])->pluck('num', 'status')->toArray();
    }


    public function updatePrepayId($orderNoOrId, $prepayId)
    {
        if (preg_match('/^\d+$/', $orderNoOrId)) {
            Order::find($orderNoOrId)->update([
                'prepay_id' => $prepayId,
            ]);
        } else {
            Order::where('order_no', $orderNoOrId)->orWhere('supplier_order_no', $orderNoOrId)->update([
                'prepay_id' => $prepayId,
            ]);
        }
    }

    public function list(mixed $status, mixed $currentPage, mixed $pageSize)
    {
        //获得模型
        $orderModel = Order::query();
        $orderModel->where([
            'user_id' => auth()['id'],
        ]);
        if ($status) {
            $orderModel->where([
                'status' => $status
            ]);
        }
        return $this->QueryHelper($orderModel, [
            'limit' => $pageSize,
            'page' => $currentPage,
            'order_field' => 'created_at',
        ])->paginate([
            'id',
            'supplier_id',
            'supplier_name',
            'supplier_order_no',
            'status',
            'snap_items',
            'total_count',
            'total_price',
            'transit_price'
        ]);
    }
}