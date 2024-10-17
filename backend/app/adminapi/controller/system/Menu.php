<?php
namespace app\adminapi\controller\system;

use app\adminapi\logic\system\MenuLogic;
use app\common\basic\BasicAdminApiController;
use app\common\service\FileLockService;
use think\facade\Db;

class Menu extends BasicAdminApiController
{
    protected $logic;
    public function __construct()
    {
        parent::__construct();
        $this->logic = new MenuLogic();
    }

    public function getMenuTree()
    {
        try{
            $getAll = input('get.getAll',0);
            $userId = $this->request->adminInfo['id'];
            $res = $this->logic->getMenuTree($userId,$getAll);
            return success($res);
        }catch(\Exception $e){
            return_error($e->getMessage(),0);// 抛出异常            
        }
    }

    public function add()
    {
        $params = input('post.');// 获取入参
        Db::startTrans();// 开启数据库事务
        try{
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('menu/add/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'title'  =>'require|max:20',
                'name'   =>'require',
                // 'perms'  =>'require',
                // 'path'   =>'require',
                'pid'    =>'require',
            ];
            if($params['type']=='M'){
                $rule['path'] = 'require';
            }
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
            $fileLock->lock('menu/edit/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'title'  =>'require|max:20',
                'name'   =>'require',
                // 'perms'  =>'require',
                // 'path'   =>'require',
                'pid'    =>'require',
            ];
            if($params['type']=='M'){
                $rule['path'] = 'require';
            }
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
        Db::startTrans();// 开启数据库事务
        try{
            $id = input('post.id',0);// 获取入参
            $fileLock = new FileLockService();//实例化文件锁用于控制访问
            $fileLock->lock('menu/delete/'.$id);
            $rule=[
                'id'    =>'require|number',
            ];
            $this->validate(input('post.'),$rule,$this->message);
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
        'title.require'=>'菜单名称为空',
        'title.max'=>'菜单名称长度过长',
        'name.require'=>'菜单标识为空',
        'perms.require'=>'权限标识为空',
    ];
}