<?php
// 应用公共文件

use app\common\model\system\ConfigModel;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\facade\Log;
use think\Response;
use think\response\Json;

if(!function_exists('buildWhere')){
    function buildWhere(array $params, array $rule){
        if(empty($params)||empty($rule)){
            return [];
        }
        $where = [];
        foreach($rule as $k=>$v){
            if(!isset($params[$k])||$params[$k]==''){
                continue;
            }
            if(is_array($v)){
                $where[] = setWhere($v[0],$v[1],$params[$k]);
            }else{
                $where[] = setWhere($k,$v,$params[$k]);
            }
        }
        return $where;
    }

    function setWhere($field,$type,$value){
        switch($type){
            case '=' : case '!=' : case '<>' : case '>' : case '>=' : 
            case '<' : case '<=' : case 'in' : case 'not in' : 
            case 'between' : case 'not between' :
                return [$field,$type,$value];
                break;
            case 'like' : case 'not like' :
                return [$field,$type,'%'.$value.'%'];
                break;
            case 'time' :
                [$start,$end] = explode(' - ',$value);
                return [$field,$type,[$start,$end]];
                break;
            case 'null' : case 'not null' :
                return [$field,$type];
                break;
        }
    }
}

if(!function_exists('return_error')){
    function return_error($msg='失败',$code=200,$data=[]){
        if(!$code){
            $result = [
                'code' => 0,
                'msg' => $msg,
            ];
            if(!empty($data)) $result['data'] = $data;
            Log::record(json_encode($result,256),'res');
            throw new HttpResponseException(json($result));
        }else{
            throw new HttpException($code,$msg);
        }
    }
}

if(!function_exists('success')){
    function success($data = [], $msg = '操作成功', $code = 1, $type = 'json', $header = [])
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
        return Response::create($result, $type)->header($header);
    }
}

if (!function_exists('error')) {
    function error($msg = '操作失败', $code = 0, $data = [], $type = 'json', $header = [])
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];

        // 如果是调试模式，添加文件和行号信息
        if (config('app.debug')) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $result['file'] = $backtrace[1]['file'];
            $result['line'] = $backtrace[1]['line'];
        }

        return Response::create($result, $type)->header($header);
    }
}


if(!function_exists('createPassword')){
    function createPassword(string $password, string $salt): string
    {
        return md5($salt . md5($password . $salt));
    }
}

if(!function_exists('fullUrl')){
    function fullUrl(string $url): string
    {
        $isSecure = false;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $isSecure = true;
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            $isSecure = true;
        }
        // 确定协议是HTTP还是HTTPS
        $protocol = $isSecure ? 'https://' : 'http://';
        // 获取主机名（包括域名和端口号）
        $host = $_SERVER['HTTP_HOST']; 
        // 构建完整的URL
        $currentUrl = $protocol . $host;
        return $currentUrl. $url;
    }
}

if(!function_exists('randomString')){
    function randomString($length, $type): string
    {
        $numbers = '0123456789';
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        switch ($type) {
            case 1: // 纯数字
                $characters = $numbers;
                break;
            case 2: // 纯字母
                $characters = $letters;
                break;
            case 3: // 数字和字母混合
                $characters = $numbers . $letters;
                break;
            default:
                return 'Invalid type specified'; // 如果输入的类型不符合预期，返回错误信息
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if(!function_exists('getConfig')){
    /**
     * 获取配置信息
     * @param  [type] $name [description]
     * @return [type]      [description]
     */
    function getConfig($name){
        $config = (new ConfigModel())->where('name',$name)->value('value');
        if(!$config){
            return_error('【'.$name.'】配置信息不存在');
        }
        return $config;
    }
}

if(!function_exists('getTree')){
    /**
     * 无限级分类
     * @param  [array|object] $obj   [数组或模型]
     * @param  [integer] $key   [当前上级id]
     * @param  [sreing]  $pKey  [子级键名]
     * @param  [sreing]  $cKey  [父级键名]
     * @param  [sreing]  $child [子级数组名]
     * @return [array]          [返回数组]
     */
    function getTree($obj,$key=0,$pKey='pid',$cKey='id',$child='children'){
        $tree = [];
        foreach($obj as $k=>$v){
            if($v[$pKey]==$key){
                $v[$child] = getTree($obj,$v[$cKey],$pKey,$cKey,$child);
                $tree[] = $v;
            }
        }
        return $tree;
    }
}