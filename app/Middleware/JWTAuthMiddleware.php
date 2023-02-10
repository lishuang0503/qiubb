<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Traits\ResponseTrait;
use Exception;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\SimpleCache\InvalidArgumentException;

class JWTAuthMiddleware implements MiddlewareInterface
{
    use ResponseTrait;

    #[Inject]
    protected ConfigInterface $config;

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        $path = $this->request->getPathInfo();
        $token = $this->request->getHeaderLine('Authorization') ?? '';
        $token = explode(' ', $token)[1] ?? '';
        p($token);
        $needLogin = !$this->matchRoute($path, 'check_route');
        throw_if($needLogin && $token == '', ErrorCode::MISSING_TOKEN);
        if ($needLogin && $token)
        {
            $user = __user($token);
            // 在HTTP头中标记登录状态
            //$response = $response->withHeader('Qiu-Authorization', 0);
            Context::set(ResponseInterface::class, $response);
            // 将用户保存到上下文中

            p($user);
            Context::set('jwt-user', $user);
            Context::set('jwt-user-token', $token);
        }
        return $handler->handle($request);
    }

    /**
     * @param string $requestPath
     * @param string $key
     * @return bool
     */
    private function matchRoute(string $requestPath, string $key): bool
    {
        $config = $this->config->get('jwt', []);
        $routes = $config[$key] ?? [];
        foreach ($routes as $route)
        {
            if (preg_match($route, $requestPath))
            {
                return false;
            }
        }
        return true;
    }
}
