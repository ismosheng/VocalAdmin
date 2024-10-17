<?php
namespace app\common\model\system;

use think\Model;

class MenuModel extends Model
{
    // 数据库表名
    protected $table = 'system_menu';

    // 定义静态变量
    public static $menuType = '';

    // 获取子菜单
    public function children()
    {
        if(self::$menuType=='M'){
            return $this->hasMany(MenuModel::class, 'pid', 'id')->where('type','M');
        }else{
            return $this->hasMany(MenuModel::class, 'pid', 'id');
        }
    }

    // 递归获取所有层级的子菜单
    public function allChildren()
    {
        // 这里同样确保使用的是 Menu
        return $this->children()->with('allChildren')->order('sort', 'asc');
    }

    // 获取完整的菜单树
    public static function getMenuTree($type='')
    {
        self::$menuType = $type;
        // 获取所有顶级菜单项
        $topMenus = self::where('pid', 0)->with('allChildren')->order('sort', 'asc')->select();

        // 由于已经使用了 with('allChildren')，这里返回的 $topMenus 已经包含了整个菜单树
        return $topMenus;
    }
}
