<?php
namespace app\common\cache;

use app\common\service\ConfigService;

class AdminAccountSafeCache extends BaseCache
{

    private $key; // 缓存次数名称
    private $minute; // 缓存时间（分钟）
    private $count;  // 允许的最大尝试次数

    public function __construct()
    {
        parent::__construct();
        $ip = \request()->ip();
        $this->key = $this->tagName . $ip;

        // 从配置服务中读取配置
        $this->minute = ConfigService::get('admin_login', 'limit_login_time', 15); // 默认15分钟
        $this->count = ConfigService::get('admin_login', 'password_error_count', 5); // 默认5次
    }

    public function record()
    {   
        
        if ($this->get($this->key)) {
            //缓存存在，记录错误次数
            $this->inc($this->key, 1);
        } else {
            //缓存不存在，第一次设置缓存
            $this->set($this->key, 1, $this->minute * 60);
        }
    }

    public function isSafe()
    {
        $count = $this->get($this->key);
        if ($count >= $this->count) {
            return false;
        }
        return true;
    }

    
    public function relieve()
    {
        $this->delete($this->key);
    }
}
