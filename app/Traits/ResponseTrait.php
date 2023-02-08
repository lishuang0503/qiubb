<?php

namespace App\Traits;
use App\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

trait ResponseTrait
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    /**
     * @param $data
     * @param string $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success($data, string $message = 'Success'): \Psr\Http\Message\ResponseInterface
    {
        $result = [
            'code' => ErrorCode::SUCCESS,
            'message' => $message,
        ];
        if (!is_null($data)) {
            if ($data instanceof \Hyperf\Resource\Json\JsonResource) {
                $data = $data->toArray();
                if (collect($data)->count() == 1) {
                    $data = current($data);
                }
            }
            $result['data'] = $data;
        }
        return $this->response->json($result);
    }

    /**
     * @param int $code
     * @param $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error(int $code, $message = null): \Psr\Http\Message\ResponseInterface
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }
        if (is_array($message)) {
            $template = ErrorCode::getMessage($code);
            foreach ($message as $key => $item) {
                $template = str_replace('#' . $key . '#', $item, $template);
            }
            $message = $template;
        }
        $result = [
            'code' => $code,
            'message' => $message,
        ];
        return $this->response->json($result);
    }

    /**
     * 向浏览器输出图片
     * @param string $image
     * @param string $type
     * @return object
     */
    public function responseImage(string $image, string $type = 'image/png'): object
    {
        return $this->response->withHeader('Server', 'GameMarket')
            ->withAddedHeader('content-type', $type)
            ->withBody(new SwooleStream($image));
    }

    /**
     * 跳转
     * @param string $toUrl
     * @param int $status
     * @param string $schema
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect(string $toUrl, int $status = 302, string $schema = 'http'): \Psr\Http\Message\ResponseInterface
    {
        return $this->response->redirect($toUrl, $status, $schema);
    }

    /**
     * 下载文件
     * @param string $filePath
     * @param string $name
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function _download(string $filePath, string $name = ''): \Psr\Http\Message\ResponseInterface
    {
        return $this->response->download($filePath, $name);
    }
}