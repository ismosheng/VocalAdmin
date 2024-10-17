<?php
namespace app\adminapi\logic\system;

use app\common\basic\BasicLogic;
use app\common\model\system\AdminRoleModel;
use app\common\model\system\MenuModel;

class AdminRoleLogic extends BasicLogic
{
    protected $model;
    public function __construct()
    {
        $this->model = new AdminRoleModel();
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
            'name'=>'like',
        ];
        $where = buildWhere($params,$whereRule); // 构建查询条件
        $page = [
            'page'      => $params['page']??1,
            'list_rows' => $params['list_rows']??10
        ];
        $page = $this->model->where($where)
                     ->where('id','>',1)
                     ->order('sort asc')
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
        $this->checkUnique($this->model,['name'=>$params['name']],$id,'角色名称已存在');
        if(!empty($params['menu_ids'])){
            $params['menu_ids'] = json_encode($params['menu_ids']);
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
    public function delete($id=0)
    {
        $count = $this->model->whereIn('id',$id)->count();
        if(empty($count)){
            return_error('数据不存在！');
        }
        $admin = $this->model->whereIn('id',$id)->select();
        foreach($admin as $a){
            $name = $a->name;
            $result = $a->delete();
            if(!$result){
                return_error('【'.$name.'】删除失败！请重试');
            }
        }
        return true;
    }
}