<?php
return [
    // JWT密钥，应该是一个复杂的字符串，建议使用环境变量获取
    'key' => env('JWT.JWT_KEY', ''),
    // 签发者
    'issuer' => 'mosheng',

    // 接收者
    'audience' => 'antdv',

    // 主题
    'subject' => 'userinfo',

    // 过期时间，以秒为单位
    'expire' => 72000, // 例如，1小时
];
?>