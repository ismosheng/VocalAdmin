<?php
declare(strict_types=1);

namespace app\common\basic;

class BasicLogic
{
    public function checkUnique($model, array $where , $id = 0 , $msg)
    {
        $count = $model::where($where)->when($id, function ($query) use ($id) {
            $query->where('id', '<>', $id);
        })->count();
        if($count > 0) return_error($msg);
        else return true;
    }
}
