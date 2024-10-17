<?php

namespace app\adminapi\logic;

use app\common\model\system\AdminModel;
use app\common\cache\AdminAccountSafeCache;
use app\common\basic\BasicLogic;
use app\common\service\JwtAuthService;

class LoginLogic extends BasicLogic
{

    protected $jwtAuth;

    public function __construct()
    {
        $this->jwtAuth = new JwtAuthService();
    }

    /**
     * 登录方法
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @param array $params
     * @return array
     */
    public function login($params):array
    {
        // 获取当前时间
        $time = date("Y-m-d H:i:s", time());
        // 查找账户是否存在
        $admin = AdminModel::where('account', $params['username'])->find();

        if (!$admin) {
            // 用户名或密码不正确
            return ['error' => true, 'msg' => '用户名或者密码不正确'];
        }
        if($admin['disable']==0){
            // 账户被禁用
            return ['error' => true,'msg' => '账户被禁用'];
        }
        // 检查账户安全性
        $accountSafeCache = new AdminAccountSafeCache();
        if (!$accountSafeCache->isSafe()) {
            // 账户不安全
            return ['error' => true, 'msg' => '失败次数太多，请稍后再试'];
        }

        // 验证密码
        if (createPassword($params['password'], $admin->salt) !== $admin->password) {
            $accountSafeCache->record();
            return ['error' => true, 'msg' => '用户名或者密码不正确'];
        }

        // 更新登录时间和IP
        $admin->login_time = $time;
        $admin->login_ip = request()->ip();
        $admin->save();

        // 登录成功，准备返回的数据
        $data['user'] = [
            'id' => $admin->id,
            'account' => $admin->account,
            'name' => $admin->name,
            'avatar' => $admin->avatar,
            
        ];
        $data['token'] = $this->jwtAuth->createToken(['id' => $admin->id, 'roles' => $admin->name]);
        return ['error' => false, 'data' => $data];
    }

}