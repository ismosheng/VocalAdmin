<?php

declare(strict_types=1);

namespace app\adminapi\logic\system;

use app\common\basic\BasicLogic;
use app\common\model\system\AdminModel;
use app\common\model\system\AdminRoleRelationModel;

class AdminLogic extends BasicLogic
{
    
    protected $model;
    public function __construct()
    {
        $this->model = new AdminModel;
    }

    /**
     * 获取用户信息带角色带菜单树
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return array
     */
    public function getUserInfo($userId):array
    {
        // 获取用户信息及其角色
        $user = AdminModel::with('roles')
                          ->field('id,is_sys,dept_id,name,avatar,account')
                          ->find($userId);

        if (!$user) {
            return ['error' => true, 'msg' => '用户不存在'];
        }
        // 返回用户信息、角色和菜单树
        return
            ['error' => false, 'data' => $user->toArray(),];
    }

    /**
     * 过滤菜单树，移除没有权限的子菜单
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @param array $menu
     * @param array $menuIds
     * @return void
     */
    protected function filterMenu($menu, $menuIds)
    {
        $menu->setRelation('children', $menu->children->filter(function ($child) use ($menuIds) {
            return in_array($child->id, $menuIds) && $this->filterMenu($child, $menuIds);
        }));
    }

    /**
     * 获取列表
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @param array $params
     * @return array
     */
    public function getList($params=[])
    {
        $whereRule = [  // 查询规则
            'account'=>'like',
            'name'=>['name','like'],
            'disable'=>'=',
            'create_time'=>'time',
        ];
        $where = buildWhere($params,$whereRule); // 构建查询条件
        $page = [
            'page'      => $params['page']??1,
            'list_rows' => $params['list_rows']??10
        ];
        $page = $this->model->where($where)
                     ->where('is_sys','<>',1)// 排除超级管理员
                     ->with(['dept','roles'])
                     ->order('id desc')
                     ->field('id,avatar,dept_id,name,avatar,account,login_time,login_ip,disable,create_time,update_time')
                     ->paginate($page)->toArray();
        return $page;
    }

    /**
     * 新增或编辑
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @param array $params
     * @param integer $id
     * @return void
     */
    public function save($params=[],$id=0)
    {
        if($id){// 编辑获取数据
            $obj = $this->model->find($id);
        }else{// 新增实例化模型
            $obj = $this->model;
        }
        // 校验数据（入参、校验规则、错误信息）
        $this->checkUnique($this->model,['name'=>$params['name']],$id,'名称已存在');
        $this->checkUnique($this->model,['account'=>$params['account']],$id,'账号已存在');
        // 盐、密码生成
        if(!empty($params['password'])){
            $params['salt'] = empty($obj->salt)?randomString(8,3):$obj->salt;
            $params['password'] = createPassword($params['password'], $params['salt']);
        }
        if(isset($params['roles'])){
            //获取已有角色关联数据
            (new AdminRoleRelationModel())::where('admin_id',$id)->delete();
            if(!empty($params['roles']) || $params['roles'] == []){
                foreach($params['roles'] as $roleId){
                    $roleData[] = [
                        'admin_id'=>$id,
                        'role_id'=>$roleId,
                    ];
                }
                (new AdminRoleRelationModel())->saveAll($roleData);
            }
        }
        
        $result = $obj->save($params);
        if($result){
            return true;
        }else{
            return_error('保存失败！请重试');
        }
    }

    /**
     * 删除 - 可多选
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        $count = $this->model->whereIn('id',$id)->count();
        if(empty($count)){
            return_error('用户不存在！');
        }
        $admin = $this->model->whereIn('id',$id)->select();
        foreach($admin as $a){
            $name = $a->name;
            if($a->is_sys){
                return_error('超级管理员不允许删除！');
            }
            $result = $a->delete();
            if(!$result){
                return_error('【'.$name.'】删除失败！请重试');
            }
        }
        return true;
    }
}
