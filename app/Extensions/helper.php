<?php

use App\Constants\ErrorCode;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Hyperf\Context\Context;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

if (!function_exists('get_container')) {

    function get_container($id)
    {
        return ApplicationContext::getContainer()->get($id);
    }
}
//输出控制台日志
if (!function_exists('p')) {
    function p($val, $title = null)
    {
        print_r('[ ' . date("Y-m-d H:i:s") . ']:');
        if ($title != null) {
            print_r("[" . $title . "]:");
        }
        print_r($val);
        print_r("\r\n");
    }
}
if (!function_exists('uuid')) {
    /**
     * @throws Exception
     */
    function uuid($length): string
    {
        if (function_exists('random_bytes')) {
            $uuid = bin2hex(\random_bytes($length));
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            $uuid = bin2hex(\openssl_random_pseudo_bytes($length));
        } else {
            $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $uuid = substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
        }
        return $uuid;
    }
}
if (!function_exists('generate_order_no')) {
    function generate_order_no(string $head): string
    {
        $yCode = range('A', 'Z');
        return $head . $yCode[intval(date('Y')) - 2021] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    }
}
if (!function_exists('throw_if')) {
    /**
     * @throws Exception
     */
    function throw_if($condition, $code)
    {
        if ($condition) {
            throw new Exception(ErrorCode::getMessage($code), $code);
        }
    }
}
if (!function_exists('auth')) {
    function auth(): array
    {
        return Context::get('jwt-user') ?? [];
    }
}
if (!function_exists('__user')) {
    /**
     * @throws Exception
     */
    function __user($token): array
    {
        $cache = get_container(CacheInterface::class);
        throw_if(!$cache->has("user-token-{$token}"), ErrorCode::MISSING_TOKEN);
        $cacheUser = $cache->get("user-token-{$token}");
        return json_decode($cacheUser, true) ?: [];
    }
}
if (!function_exists('user_cache_update')) {
    /**
     * @throws Exception
     */
    function user_cache_update($token, $key, $value): bool
    {
        $cache = get_container(CacheInterface::class);
        if ($cache->has("user-token-{$token}")) {
            $cacheUser = json_decode($cache->get("user-token-{$token}"), true) ?: [];
            $cacheUser[$key] = $value;
            Context::set('micro-user', $cacheUser);
            return $cache->set("user-token-{$token}", json_encode($cacheUser), 7200);
        }
        return true;
    }
}
if (!function_exists('curl_get')) {
    function curl_get($url)
    {
        try {
            $response = make(ClientFactory::class)->create([
                'headers' => [
                    //'Authorization' => $authorization,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ])->get($url);
            $contents = $response->getBody()->getContents();
            p($contents);
            if ($response->getStatusCode() === 200) {
                $res = json_decode((string)$contents, true);
            } else {
                $res = [];
            }
        } catch (GuzzleException $e) {
            p($e->getMessage());
            $res = [];
        }
        return $res;
    }
}

if (!function_exists('curl_post')) {
    function curl_send($url, $params)
    {
        try {
            $response = make(ClientFactory::class)->create([
                'headers' => [
                    //'Authorization' => $authorization,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ])->post($url, [
                'body' => json_encode($params)
            ]);
            $contents = $response->getBody()->getContents();
            p($contents);
            if ($response->getStatusCode() === 200) {
                $res = json_decode((string)$contents, true);
            } else {
                $res = [];
            }
        } catch (GuzzleException $e) {
            p($e->getMessage());
            $res = [];
        }
        return $res;
    }
}


if (!function_exists('rpc_send')) {
    function rpc_send($baseUri, $path, $method, $params, $auth = [])
    {
        try {
            $data[RequestOptions::JSON] = [
                'jsonrpc' => '2.0',
                'id' => time(),
                'method' => $method,
                'params' => $params
            ];
            if ($auth) {
                $data['auth'] = $auth;
            }
            $response = make(ClientFactory::class)->create([
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'base_uri' => $baseUri
            ])->post($path, $data);
            $contents = $response->getBody()->getContents();
            if ($response->getStatusCode() === 200) {
                $res = json_decode((string)$contents, true);
            } else {
                $res = [];
            }
        } catch (GuzzleException $e) {
            p($e->getMessage());
            $res = [];
        }

        return $res;

    }
}



