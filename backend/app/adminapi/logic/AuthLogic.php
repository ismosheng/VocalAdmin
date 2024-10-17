<?php

namespace app\adminapi\logic;

use app\common\basic\BasicLogic;
use app\common\model\system\AdminRoleModel;
use app\common\model\system\AdminRoleRelationModel;
use app\common\model\system\MenuModel;
class AuthLogic extends BasicLogic
{
    /**
     * 获取所有权限
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return array
     */
    public function getAllAuth():array
    {
        return MenuModel::distinct(true)
            ->where([
                ['is_disable', '=', 1],
                ['perms', '<>', '']
            ])
            ->column('perms');
    }

    /**
     * 根据ID获取权限
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return array
     */
    public function getAuthByAdminId(int $id): array
    {
        // 获取角色ID
        $role = AdminRoleRelationModel::where('admin_id', $id)->column('role_id');
        $menu = AdminRoleModel::whereIn('id', $role)->column('menu_ids');
        
        // 如果$menu包含通配符，选择所有符合条件的菜单
        if (in_array('["*"]', $menu)) {
            $perms = MenuModel::distinct(true)
                ->where([
                    ['is_disable', '=', '1'],
                    ['perms', '<>', '']
                ])
                ->column('perms');
        } else {
            $menuIds = [];
            foreach ($menu as $m) {
                $menuIds = array_merge($menuIds, json_decode($m, true));
            }
            $menuIds = array_unique($menuIds);
            // 否则，使用$menu中的ID进行查询
            $perms = MenuModel::distinct(true)
                ->where([
                    ['is_disable', '=', '1'],
                    ['perms', '<>', ''],
                    ['id', 'in', $menuIds]
                ])->column('perms');

        }
        return $perms;
    }

    /**
     * 获取用户菜单树
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-25
     * @param integer $userId
     * @return object
     */
    public function getUserMenu($userId=0)
    {
        if(!$userId){
            return error('用户不存在');
        }
        $role = AdminRoleRelationModel::where('admin_id', $userId)->column('role_id');
        $menu = AdminRoleModel::whereIn('id', $role)->column('menu_ids');
        if (in_array('["*"]', $menu)) {
            $menus = MenuModel::getMenuTree();
            $perms = MenuModel::where('type','M')->column('perms');
        }else{
            $menuIds=[];
            foreach($menu as $m){
                $menuIds = array_merge($menuIds,json_decode($m,true));
            }
            $menuIds = array_unique($menuIds);
            $menus = MenuModel::whereIn('id', $menuIds)->where('type','M')->where('pid', 0)->with('allChildren')->whereIn('id',$menuIds)->order('sort', 'asc')->select();
            $perms = MenuModel::whereIn('id', $menuIds)->where('type','M')->column('perms');
            // 过滤掉没有权限的子菜单
        }

        return ['menus'=>$menus,'perms'=>$perms];
    }

}