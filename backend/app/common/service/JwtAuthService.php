<?php
namespace app\common\service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\facade\Config;

class JwtAuthService
{
    private $key; // JWT的密钥
    private $issuer; // 签发者
    private $audience; // 接收者
    private $subject; // 主题
    private $expire; // 过期时间

    public function __construct()
    {
        // 从配置文件或环境变量中读取JWT相关配置
        $this->key = Config::get('jwt.key');
        $this->issuer = Config::get('jwt.issuer');
        $this->audience = Config::get('jwt.audience');
        $this->subject = Config::get('jwt.subject');
        $this->expire = Config::get('jwt.expire');
    }

    // 创建JWT
    public function createToken($data)
    {
        $time = time();
        $token = [
            'iss' => $this->issuer, // 签发者
            'aud' => $this->audience, // 接收者
            'sub' => $this->subject, // 主题
            'iat' => $time, // 签发时间
            'exp' => $time + $this->expire, // 过期时间
            'data' => $data // 用户数据
        ];
        return JWT::encode($token, $this->key, 'HS256');
    }

    // 解析JWT
    public function decodeToken($jwt)
    {
        try {
            // 将JWT字符串解码成对象
            return JWT::decode($jwt, new Key($this->key, 'HS256'));
        } catch (\Exception $e) {
            // 解码失败，可以处理异常，例如返回错误信息
            return null;
        }
    }

    // 验证JWT
    public function verifyToken($jwt)
    {
        $decoded = $this->decodeToken($jwt);
        // 根据解码结果判断是否验证成功
        return $decoded !== null;
    }

    public function getUserIdFromToken($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            return $decoded->data->id; // 假设用户ID存储在data->id中
        } catch (\Exception $e) {
            // 解析失败，返回null或抛出异常
            return null;
        }
    }
}
