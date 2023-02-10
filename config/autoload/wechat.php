<?php
/**
 * Created by shuang.li on  2023/2/8 4:01 PM.
 * Current file name wechat.php
 */

//248c50789429a7c0154394a250f12c0e  wx60adf6478e1c9612 公众号
//APIV3 82f7d51db93d5a8d5d01276f7f3d5aac

return [
    'shop'=>[

        // 小程序 APPID
        'mini_app_id' => 'wx86c80e8ce4887c66',

        // APP 引用的 appid
        'app_id' =>'wx86c80e8ce4887c66',

        // 微信支付分配的微信商户号
        'mch_id' =>'1604745662',

        // 微信支付异步通知地址
        'notify_url' => 'http://wx_duoduo.local/wx/pay-back',

        // 微信支付签名秘钥
        'key' => '82f7d51db93d5a8d5d01276f7f3d5aac',

        // 必填-商户秘钥
        'mch_secret_key' => '82f7d51db93d5a8d5d01276f7f3d5aac',
        // 必填-商户私钥 字符串或路径
        'mch_secret_cert' => '/User/lishuang/cert/apiclient_cert.pem',
        // 必填-商户公钥证书路径
        'mch_public_cert_path' => '/User/lishuang/cert/apiclient_key.pem',

    ]
];
