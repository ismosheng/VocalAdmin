<?php
declare(strict_types=1);

namespace app\common\basic;

use think\Validate;
use think\exception\ValidateException; // 引入验证异常类

class BasicValidate extends Validate
{
    public string $method = 'GET';

    public function post()
    {
        if (!$this->request->isPost()) {
            throw new ValidateException('请求方式错误');
        }
        $this->method = 'POST';
        return $this;
    }

    public function get()
    {
        if (!$this->request->isGet()) {
            throw new ValidateException('请求方式错误');
        }
        return $this;
    }

    public function put()
    {
        if (!$this->request->isPut()) {
            throw new ValidateException('请求方式错误');
        }
        return $this;
    }

    public function delete()
    {
        if (!$this->request->isDelete()) {

            throw new ValidateException('请求方式错误');
        }
        return $this;
    }


    public function goCheck($scene = null, array $validateData = []): array
    {
        //接收参数
        if ($this->method == 'GET') {
            $params = request()->get();
        } else {
            $params = request()->post();
        }
        //合并验证参数
        $params = array_merge($params, $validateData);

        //场景
        if ($scene) {
            $result = $this->scene($scene)->check($params);
        } else {
            $result = $this->check($params);
        }

        if (!$result) {
            // 如果验证失败，返回错误信息
            return [
                'error' => true,
                'msg' => is_array($this->error) ? implode(';', $this->error) : $this->error,
                'data' => []
            ];
        }
        // 3.成功返回数据
        return [
            'error' => false,
            'data' => $params
        ];
    }
}
