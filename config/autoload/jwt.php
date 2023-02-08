<?php
declare(strict_types=1);
return [
    'check_route'  => [
        "/\/account\/modifyNickname/i",
    ],
    // 只检测AppKey有效性，不进行签名验证
    'key_route'    => [],
    // 针对AppKey和签名验证
    'verify_route' => [],
];