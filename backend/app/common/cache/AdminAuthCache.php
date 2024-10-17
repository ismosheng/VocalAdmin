<?php

namespace app\common\cache;

use app\adminapi\logic\AuthLogic;

class AdminAuthCache extends BaseCache
{

    private $prefix = 'admin_auth_';
    private $authConfigList = [];
    private $cacheMd5Key = '';      //权限文件MD5的key
    private $cacheAllKey = '';      //全部权限的key
    private $cacheUrlKey = '';       //管理员的url缓存key
    private $authMd5 = '';          //权限文件MD5的值
    private $adminId = '';
    protected $authLogic;
    public function __construct($adminId = '')
    {
        parent::__construct();

        $this->adminId = $adminId;
        $this->authLogic = new AuthLogic();
        // 以下操作都依赖于父类构造函数已经成功执行
        $this->authConfigList = $this->authLogic->getAllAuth();
        
        $this->authMd5 = md5(json_encode($this->authConfigList));

        $this->cacheMd5Key = $this->prefix . 'md5';
        $this->cacheAllKey = $this->prefix . 'all';
        $this->cacheUrlKey = $this->prefix . 'url_' . $this->adminId;

        $cacheAuthMd5 = $this->get($this->cacheMd5Key);
        $cacheAuth = $this->get($this->cacheAllKey);

        if ($this->authMd5 !== $cacheAuthMd5 || empty($cacheAuth)) {
            $this->deleteTag();
        }
    }


    /**
     * 获取所有
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return void
     */
    public function getAllUrl()
    {
        // 先从缓存中获取
        $cacheAuth = $this->get($this->cacheAllKey);
        if ($cacheAuth) {
            return $cacheAuth;
        }
        //获取全部权限
        $authList = $this->authLogic->getAllAuth();
        //保存到缓存并读取返回
        $this->set($this->cacheMd5Key, $this->authMd5);
        $this->set($this->cacheAllKey, $authList);
        return $authList;
    }

    /**
     * 获取管理员URL
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return void
     */
    public function getAdminUrl()
    {
        // 先从缓存中获取
        $cacheAuth = $this->get($this->cacheUrlKey);
        if ($cacheAuth) {
            return $cacheAuth;
        }
        //获取全部权限
        $authList = $this->authLogic->getAuthByAdminId($this->adminId);
        //保存到缓存并读取返回
        if (empty($authList)) {
            return [];
        }
        $this->set($this->cacheUrlKey, $authList, 3600);

        //保存到缓存并读取返回
        return $authList;
    }

    /**
     * 清楚缓存
     *
     * @Author 默笙
     * @DateTime 2024-09-25
     * @return void
     */
    public function clearAuthCache()
    {
        $this->clear($this->cacheUrlKey);
        return true;
    }
    
}