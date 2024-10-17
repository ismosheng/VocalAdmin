<?php
// 中间件配置
return [
    // 别名或分组
    'alias'    => [
        'jwt' => \app\middleware\JwtMiddleware::class,
        'log' => \app\middleware\LogMiddleware::class,
        'auth' => \app\middleware\AuthMiddleware::class,
    ],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => ['jwt','auth','log','exception'],
];
