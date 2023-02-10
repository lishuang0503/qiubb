<?php

namespace App\Services\Shop;

use App\Model\Shop\Order;
use App\Model\Shop\UserStatement;
use App\Services\BaseService;
use Carbon\Carbon;
use Exception;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Yansongda\HyperfPay\Pay;

class PayBackService extends BaseService
{

    #[Inject]
    public Pay $pay;

    public function notify(): bool
    {
        DB::beginTransaction();
        try {
            $data = $this->pay->wechat()->callback();
            $orderNo = $data['out_trade_no'];
            $wxTotalFee = $data['total_fee'];
            $transactionId = $data['transaction_id'];
            //获取订单信息
            $builder = Order::where([
                'order_no' => $orderNo,
                'status' => Order::ORDER_WAIT_PAY,
            ])->orWhere('supplier_order_no', $orderNo);
            $orders = $builder->select([
                'id',
                'supplier_order_no',
                'supplier_id',
                'user_id',
                'total_price',
            ])->get();
            if ($orders->isEmpty()) {
                return true;
            }
            //更新订单
            $builder->update([
                'status' => Order::ORDER_WAIT_DELIVERY,
                'transaction_id' => $transactionId,
                'pay_time' => Carbon::now()->toDateTimeString(),
            ]);
            //更新流水表
            $statements = $orders->map(function ($item) use ($wxTotalFee, $transactionId) {
                $item['total_fee'] = bcdiv($wxTotalFee, 100, 6);
                $item['transaction_id'] = $transactionId;
                $item['order_id'] = $item['id'];
                unset($item['id']);
                return $item;
            })->toArray();
            $supplierOrderNo = $orders->pluck('supplier_order_no')->toArray();
            if (UserStatement::whereIn('supplier_order_no', $supplierOrderNo)->exists()) {
                throw new Exception('该流水纪录已经生成supplier_order_no=' . $supplierOrderNo);
            }
            UserStatement::insert($statements);
            DB::commit();
            return true;
        } catch (\Throwable $throwable) {
            Db::rollback();
            //发送邮件
            // Log::info($throwable->getMessage());
            // 如果出现异常，向微信返回false，请求重新发送通知
            return false;
        }
    }
}