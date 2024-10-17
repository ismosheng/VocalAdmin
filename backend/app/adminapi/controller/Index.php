<?php

namespace app\adminapi\controller;

use app\BaseController;
use app\common\service\FileLockService;
use think\facade\Db;

class Index extends BaseController
{
    /**
     * 这是一个示例，用于展示【访问锁、事物、验证器】的标准模版
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-25
     * @return json
     */
    public function index()
    {
        $fileLock = new FileLockService();//实例化文件锁用于控制访问
        $fileLock->lock('test');//test为用于控制访问的唯一标识
        Db::startTrans();// 开启数据库事务
        try{
            $params = input('get.');// 获取入参
            // 校验数据（入参、校验规则、错误信息）
            $this->validate($params,[
                'name' =>'require|max:20',
            ],$this->message);
            sleep(5);
            Db::commit();
            return success($params);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return error($e->getMessage());// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }

    // 验证器的错误信息
    public $message = [
        'name.require'=>'管理员名称为空',
        'name.max'=>'管理员名称长度过长',
        'account.require'=>'账号为空',
        'password.require'=>'密码为空',
    ];

}
