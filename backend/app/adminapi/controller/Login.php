<?php

namespace app\adminapi\controller;

use app\adminapi\logic\LoginLogic;
use app\adminapi\validate\LoginValidate;
use app\common\basic\BasicAdminApiController;
use think\facade\Log;

class Login extends BasicAdminApiController
{
    protected $loginLogic;

    public function __construct()
    {
        $this->loginLogic = new LoginLogic();
    }
    /**
     * 登录
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return void
     */
    public function login()
    {
        try {
            $validateResult = (new LoginValidate())->post()->goCheck();

            if (!empty($validateResult['error'])) {
                return error($validateResult['msg']);
            }

            $params = $validateResult['data'];
            $loginResult = $this->loginLogic->login($params);

            if ($loginResult['error']) {
                return error($loginResult['msg']);
            }

            return success($loginResult['data']);
        } catch (\Exception $e) {
            // 验证过程中抛出的异常
            return error($e->getMessage());
        }
    }
}
