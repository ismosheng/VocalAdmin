<?php
namespace app\adminapi\controller;

use app\adminapi\logic\AuthLogic;
use app\common\basic\BasicAdminApiController;

class Auth extends BasicAdminApiController
{
    protected $logic;
    public function __construct()
    {
        parent::__construct();
        $this->logic = new AuthLogic();
    }
    /**
     * 获取用户菜单树
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-25
     * @return json
     */
    public function getUserMenu()
    {
        $userId = $this->request->adminInfo['id'];

        $result = $this->logic->getUserMenu($userId);
        return success($result);
    }
}