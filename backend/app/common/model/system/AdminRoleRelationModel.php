<?php

namespace app\common\model\system;

use think\Model;
use think\model\Pivot;

class AdminRoleRelationModel extends Pivot
{
    // 数据库表名
    protected $table = 'system_admin_role_relation';

}
