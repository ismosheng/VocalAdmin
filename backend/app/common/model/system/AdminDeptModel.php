<?php

namespace app\common\model\system;

use think\Model;
use think\model\concern\SoftDelete;

class AdminDeptModel extends Model
{
    use SoftDelete;
    // 数据库表名
    protected $table = 'system_admin_dept';

    public function children()
    {
        return $this->hasMany(AdminDeptModel::class, 'pid', 'id');
    }

    // 递归获取所有层级的子菜单
    public function allChildren()
    {
        return $this->children()->with('allChildren')->order('sort', 'asc');
    }

    public function getTree()
    {
        $deptTree = self::where('pid', 0)->with('allChildren')->order('sort', 'asc')->select();

        return $deptTree;
    }
}
