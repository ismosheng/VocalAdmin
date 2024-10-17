<?php

namespace app\common\model\system;

use think\model\concern\SoftDelete;
use app\common\model\system\AdminDeptModel;
use think\Model;

class AdminModel extends Model
{
    use SoftDelete;
    
    // 数据库表名
    protected $table = 'system_admin';
    protected $deleteTime = 'delete_time';

    // 定义与 AdminDept 的关联
    public function dept()
    {
        return $this->belongsTo(AdminDeptModel::class, 'dept_id', 'id')->withDefault(['name' => '/']);
    }

    // 定义与 AdminRole 的多对多关联
    public function roles()
    {
        return $this->belongsToMany(AdminRoleModel::class, AdminRoleRelationModel::class, 'role_id', 'admin_id');
    }

    //public function getAvatarAttr($value)
    //{
    //    return fullUrl($value);
    //}
}
