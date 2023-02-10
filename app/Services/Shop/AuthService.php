<?php

namespace App\Services\Shop;

use App\Constants\ErrorCode;
use App\Model\Shop\User;
use App\Services\BaseService;
use Exception;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class AuthService extends BaseService
{

    #[Inject]
    protected CacheInterface $cache;


    public function login(mixed $code): string
    {
        //mch_id = 1604745662
        $data = curl_get('https://api.weixin.qq.com/sns/jscode2session?appid=wx86c80e8ce4887c66&secret=82f7d51db93d5a8d5d01276f7f3d5aac&js_code='.$code.'&grant_type=authorization_code');
        throw_if(!$data || !isset($data['openid']), ErrorCode::LOGIN_FAILED);

        $user = User::firstOrCreate([
            'openid' => $data['openid']
        ]);
        $cacheUser = $this->cacheUser($user);
        return $this->generateToken($cacheUser);
    }

    protected function generateToken(array $cacheUser): string
    {
        $token = sha1(uniqid());
        $this->cache->set("user-token-{$token}", json_encode($cacheUser), 7200);
        return $token;
    }

    protected function cacheUser(User $user): array
    {
        $cacheUser = [
            'id' => $user->id,
            'openid' => $user->openid,
        ];
        Context::set('jwt-user', $cacheUser);
        return $cacheUser;
    }

    public function verify(mixed $token): bool
    {
        return $this->cache->has($token);
    }

}