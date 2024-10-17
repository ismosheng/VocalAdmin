<?php
namespace app\adminapi\logic\system;

use app\common\basic\BasicLogic;
use app\common\model\system\AdminRoleModel;
use app\common\model\system\AdminRoleRelationModel;
use app\common\model\system\MenuModel;

class MenuLogic extends BasicLogic
{
    
    protected $model;
    public function __construct()
    {
        $this->model = new MenuModel();
    }

    public function getMenuTree($userId,$getAll)
    {
        if($getAll){
            $result = MenuModel::getMenuTree();
        }else{
            if(!$userId){
                return_error('用户不存在');
            }
            $role = AdminRoleRelationModel::where('admin_id', $userId)->column('role_id');
            $menu = AdminRoleModel::whereIn('id', $role)->column('menu_ids');
            if (in_array('["*"]', $menu)) {
                $menus = MenuModel::getMenuTree('M');
                $perms = MenuModel::where('type','B')->column('name');
            }else{
                $menuIds=[];
                foreach($menu as $m){
                    $menuIds = array_merge($menuIds,json_decode($m,true));
                }
                $menuIds = array_unique($menuIds);
                
                $menus = MenuModel::whereIn('id', $menuIds)->where('type','M')->order('sort', 'asc')->select();
                $menus = getTree($menus,0,'pid','id','allChildren');
                
                $perms = MenuModel::whereIn('id', $menuIds)->where('type','B')->column('name');
                // 过滤掉没有权限的子菜单
            }
            $result = ['menus'=>$menus,'perms'=>$perms];
        }

        return $result;
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
        if($params['type']=='M'){
            $this->checkUnique($this->model,['title'=>$params['title']],$id,'菜单名称已存在');
        }
        $this->checkUnique($this->model,['name'=>$params['name']],$id,'菜单标识已存在');
        // if(!empty($params['perms'])){
        //     $this->checkUnique($this->model,['perms'=>$params['perms']],$id,'权限标识已存在');
        // }

        $result = $obj->save($params);
        if($result){
            return true;
        }else{
            return_error('保存失败！请重试');
        }
    }

    /**
     * 删除
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @param string $id
     * @return void
     */
    public function delete($id=0)
    {
        $admin = $this->model->find($id);
        if(empty($admin)){
            return_error('数据不存在！');
        }
        $name = $admin->name;
        $childCount = $this->model->where('pid',$id)->count();
        if($childCount > 0){
            return_error('【'.$name.'】存在子级，不可删除！');
        }
        $result = $admin->delete();
        if(!$result){
            return_error('【'.$name.'】删除失败！请重试');
        }
        return true;
    }
}