<?php

namespace app\adminapi\controller\system;

use think\facade\Db;
use app\common\service\FileLockService;
use app\adminapi\logic\system\AdminRoleLogic;
use app\common\basic\BasicAdminApiController;

class AdminRole extends BasicAdminApiController
{
    protected $logic;
    public function __construct()
    {
        parent::__construct();
        $this->logic = new AdminRoleLogic();
    }
    
    /**
     * 获取分页列表
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @return json
     */
    public function getList()
    {
        try{
            $result = $this->logic->getList(input('get.'));
            return success($result);
        }catch(\Exception $e){
            return error($e->getMessage());// 抛出异常
        }
    }

    /**
     * 新增或编辑
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @return json
     */
    public function add()
    {
        $params = input('post.');// 获取入参
        Db::startTrans();// 开启数据库事务
        try{
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('role/save/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'menu_ids' =>'require',
            ];
            $this->validate($params,$rule,$this->message);
            $result = $this->logic->save($params);

            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return error($e->getMessage());// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }
    public function edit()
    {
        $params = input('post.');// 获取入参
        Db::startTrans();// 开启数据库事务
        try{
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('role/save/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'menu_ids' =>'require',
            ];
            $this->validate($params,$rule,$this->message);
            $result = $this->logic->save($params,$params['id']);
                
            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return error($e->getMessage());// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }

    /**
     * 删除 - 可多选
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @return void
     */
    public function delete()
    {
        $id = input('post.id');// 获取入参
        if(empty($id)){
            return error('请选择要删除的数据');
        }
        Db::startTrans();// 开启数据库事务
        try{
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('role/delete/'.$id);
            $result = $this->logic->delete($id);
            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return error($e->getMessage());// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }



    // 验证器的错误信息
    public $message = [
        'id.require'=>'id为空',
        'name.require'=>'角色名称为空',
        'name.max'=>'角色名称长度过长',
        'menu_ids.require'=>'菜单权限为空',
    ];
}