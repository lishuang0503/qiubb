<?php
declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Traits\ResponseTrait;
use Hyperf\Context\Context;

abstract class AbstractController
{
    use ResponseTrait;

    /**
     * 获取当前登录用户
     */
    protected function user(): object
    {
        $userArr = Context::get('micro-user');
        return (object)$userArr;
    }

    /**
     * 获取请求参数
     * @param string ...$keys
     * @return array
     */
    protected function params(...$keys): array
    {
        $values = [];
        foreach ($keys as $key)
        {
            $values[] = match ($key)
            {
                // '__user' => $this->user(),
                'page' => $this->request->input($key, 1),
                'pagesize' => $this->request->input($key, 20),
                default => $this->request->input($key, ''),
            };
        }
        return $values;
    }
}
