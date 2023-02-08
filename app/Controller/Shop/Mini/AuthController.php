<?php

namespace App\Controller\Shop\Mini;
use App\Controller\Shop\BaseController;
use App\Services\Shop\AuthService;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;

#[AutoController("/shopMini/auth")]
class AuthController extends BaseController
{
    #[Inject]
    protected AuthService $authService;

    /**
     * @throws Exception
     */
    public function login(): ResponseInterface
    {

        [$code] = $this->params('code');
        $token = $this->authService->login($code);
        return $this->success(compact('token'));
    }


    public function verify(): ResponseInterface
    {
        [$token] = $this->params('token');
        $isValid = $this->authService->verify($token);
        return $this->success(compact('isValid'));
    }
}