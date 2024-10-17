<?php

declare(strict_types=1);
namespace app\middleware;

use think\Request;
use think\exception\HttpException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\adminapi\logic\system\AdminLogic;

class JwtMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        try {
            // 从请求中获取JWT，通常从Authorization头部中获取
            $authHeader = $request->header('Authorization');
            if (!$authHeader) {
                return_error('Access token required',500);
            }
    
            // 假设token是以'Bearer '为前缀
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
            
            // 这里需要替换成你的密钥
            $secretKey = env('JWT.JWT_KEY', '');
            // 解码JWT
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            // 可以在这里添加更多的验证逻辑，例如验证iss, aud或其他claims
            $admin = new AdminLogic();
            $admininfo = $admin->getUserInfo($decoded->data->id);
            if($admininfo['error']){
                throw new HttpException(401, '用户信息不存在 ');
                return json(['code' => 401,'msg' => '用户信息不存在 '],401);
            }
            
            $request->jwt = $decoded;
            $request->adminInfo = $admininfo['data'];
            //$decoded->userInfo = $userinfo;
            //app('user_id', $decoded->data->id);
            // 如果一切正常，则继续请求
            return $next($request);
        } catch (\Exception $e) {
            return json(['code' => 401, 'msg' =>  $e->getMessage()], 401);
            // 如果验证失败，抛出异常
            throw new HttpException(401, 'Invalid token: ' . $e->getMessage());
        }
    }
}
