<?php

declare(strict_types=1);

namespace app\adminapi\validate;

use think\Validate;
use app\common\service\ConfigService;
use app\common\cache\AdminAccountSafeCache;
use app\common\basic\BasicValidate;
class LoginValidate extends BasicValidate
{
    protected $rule = [];
    protected $message = [];

    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        parent::__construct($rules, $message, $field);

        // 载入登录相关的配置
        $this->loadLoginConfigurations();
    }

    protected function loadLoginConfigurations(): void
    {
        $loginRestrictions = ConfigService::get('admin_login', 'login_restrictions', 0);
        $passwordErrorCount = ConfigService::get('admin_login', 'password_error_count', 5);
        $limitLoginTime = ConfigService::get('admin_login', 'limit_login_time', 30);

        if ($loginRestrictions) {
            $this->rule['username'] = 'require';
            $this->rule['password'] = 'require|checkPasswordErrorCount';

            // 设置自定义消息
            $this->message['username.require'] = '请输入用户名';
            $this->message['password.require'] = '请输入密码';
            $this->message['password.checkPasswordErrorCount'] = '账号已被锁定请于 ' . $limitLoginTime . ' 分钟后重试.';
        } else {
            $this->rule['username'] = 'require';
            $this->rule['password'] = 'require';


            // 设置自定义消息
            $this->message['username.require'] = '请输入用户名';
            $this->message['password.require'] = '请输入密码';
        }
    }

    protected function checkPasswordErrorCount($value, $rule, $data = [])
    {
        $accountSafeCache = new AdminAccountSafeCache();
        if (!$accountSafeCache->isSafe()) {
            // 获取配置中设置的锁定时间
            $limitLoginTime = ConfigService::get('admin_login', 'limit_login_time', 30);
            // 设置错误消息
            $this->message['password.checkPasswordErrorCount'] = '账号已被锁定，请于 ' . $limitLoginTime . ' 分钟后重试。';
            return false;
        }
        return true;
    }
}
