<?php

namespace App\Controller\Shop\Mini;

use App\Controller\Shop\BaseController;
use App\Services\Shop\PayBackService;
use Hyperf\HttpServer\Annotation\AutoController;

#[AutoController("/shopMini/payBack")]
class PayBackController extends BaseController
{
    protected PayBackService $payBackService;

    //支付回调
    public function notify(): bool
    {
        return $this->payBackService->notify();
    }

}