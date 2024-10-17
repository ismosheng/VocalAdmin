<?php
namespace app\adminapi\logic\system;

use app\common\basic\BasicLogic;
use app\common\model\system\AdminDeptModel;

class AdminDeptLogic extends BasicLogic
{
    
    protected $model;
    public function __construct()
    {
        $this->model = new AdminDeptModel();
    }
    /**
     * 获取树形列表
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-26
     * @param array $params
     * @return array
     */
    public function getTree()
    {
        $tree = $this->model->getTree();
        return $tree;
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
        $this->checkUnique($this->model,['name'=>$params['name']],$id,'部门名称已存在');

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