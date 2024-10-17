<?php

namespace app\common\model\system;

use think\facade\Log;
use think\Model;
use think\model\concern\SoftDelete;

class AdminRoleModel extends Model
{
    use SoftDelete;
    // 数据库表名
    protected $table = 'system_admin_role';

    // 定义一个方法来获取角色的菜单权限
    // public function getMenuIdsAttr($value)
    // {
    //     $ids = json_decode($value, true);
    //     if(in_array('*', $ids)){
    //         return (new MenuModel())->select();
    //     }else{
    //         return (new MenuModel())->where('id', 'in', $ids)->select();
    //     }
    // }


}
