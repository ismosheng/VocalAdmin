<?php
namespace app\adminapi\controller\system;

use think\facade\Db;
use app\common\service\FileLockService;
use app\adminapi\logic\system\AdminLogic;
use app\common\basic\BasicAdminApiController;

class Admin extends BasicAdminApiController
{
    protected $logic;
    public function __construct()
    {
        parent::__construct();
        $this->logic = new AdminLogic();
    }
    /**
     * 获取用户信息
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return void
     */
    public function getUserInfo()
    {
        $result = $this->logic->getUserInfo($this->request->adminInfo['id']);
        if($result['error']){
            return error($result['msg']);
        }else{
            return success($result['data']);
        }
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
            $fileLock->lock('admin/add/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'account' =>'require|max:20',
            ];
            if(empty($params['id'])){
                $rule['password'] = 'require';
            }
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
            $fileLock->lock('admin/edit/'.$params['name']);
            // 校验数据（入参、校验规则、错误信息）
            $rule=[
                'name'    =>'require|max:20',
                'account' =>'require|max:20',
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
            $fileLock->lock('admin/delete/'.$id);
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
        'name.require'=>'管理员名称为空',
        'name.max'=>'管理员名称长度过长',
        'account.require'=>'账号为空',
        'password.require'=>'密码为空',
    ];
}