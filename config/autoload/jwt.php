<?php
declare(strict_types=1);
return [
    'check_route'  => [
        "/\/shopMini\/order\/pay/i",
        "/\/shopMini\/order\/generate/i",
        "/\/shopMini\/order\/delete/i",
        "/\/shopMini\/order\/detail/i",
        "/\/shopMini\/order\/countsByStatus/i",
        "/\/shopMini\/order\/list/i",
    ],
    // 只检测AppKey有效性，不进行签名验证
    'key_route'    => [],
    // 针对AppKey和签名验证
    'verify_route' => [],
];