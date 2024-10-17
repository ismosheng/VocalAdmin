<?php
namespace app\adminapi\controller\system;

use app\adminapi\logic\system\AdminDeptLogic;
use think\facade\Db;
use app\common\service\FileLockService;
use app\common\basic\BasicAdminApiController;

class AdminDept extends BasicAdminApiController
{
    protected $logic;
    public function __construct()
    {
        parent::__construct();
        $this->logic = new AdminDeptLogic();
    }

    /**
     * 获取分页列表
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @return json
     */
    public function getTree()
    {
        try{
            $result = $this->logic->getTree();
            return success($result);
        }catch(\Exception $e){
            return_error($e->getMessage(),0);// 抛出异常
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
            $fileLock->lock('dept/add/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'pid' =>'require',
            ];
            $this->validate($params,$rule,$this->message);
            $result = $this->logic->save($params);

            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return_error($e->getMessage(),0);// 抛出异常
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
            $fileLock->lock('dept/edit/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'pid' =>'require',
            ];
            $this->validate($params,$rule,$this->message);
            $result = $this->logic->save($params,$params['id']);

            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return_error($e->getMessage(),0);// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }

    /**
     * 删除
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @return void
     */
    public function delete()
    {
        Db::startTrans();// 开启数据库事务
        try{
            $id = input('post.id',0);// 获取入参
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('dept/delete/'.$id);
            $rule=[
                'id'    =>'require|number',
            ];
            $this->validate(input('post.'),$rule,$this->message);
            $result = $this->logic->delete($id);
            Db::commit();
            return success($result);
        }catch(\Exception $e){// 捕获异常回滚并返回json
            Db::rollback();
            return_error($e->getMessage(),0);// 抛出异常
        }finally{
            $fileLock->unlock();// 解锁
        }
    }



    // 验证器的错误信息
    public $message = [
        'id.require'=>'id为空',
        'id.number'=>'id必须为数字',
        'name.require'=>'部门名称为空',
        'name.max'=>'部门名称长度过长',
        'pid.require'=>'上级部门为空',
    ];


}