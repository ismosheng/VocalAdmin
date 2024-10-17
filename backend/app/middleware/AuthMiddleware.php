<?php

declare(strict_types=1);

namespace app\middleware;
use app\common\cache\AdminAuthCache;
use think\helper\Str;

class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        //系统默认超级管理员，无需权限验证
        if (1 === $request->adminInfo['is_sys']) {
            return $next($request);
        }
        $adminAuthCache = new AdminAuthCache($request->adminInfo['id']);
        
        // 当前访问路径
        $accessUrl = strtolower($request->controller() . '/' . $request->action());

        // 全部路由
        $allUrl = $this->formatUrl($adminAuthCache->getAllUrl());

        // 判断该当前访问的uri是否存在，不存在无需验证
        if (!in_array($accessUrl, $allUrl)) {
            return $next($request);
        }

        // 当前管理员拥有的路由权限
        $AdminUrls = $adminAuthCache->getAdminUrl() ?? [];
        $AdminUrls = $this->formatUrl($AdminUrls);

        if (in_array($accessUrl, $AdminUrls)) {
            return $next($request);
        }

        return error('权限不足，请联系管理员');

    }

    /**
     * 格式化URL
     *
     * @Author 擎天柱
     * @DateTime 2024-09-25
     * @param [type] $url
     * @return void
     */
    public function formatUrl(array $data)
    {
        return array_map(function ($item) {
            return strtolower(Str::camel($item));
        }, $data);
    }
}
?>