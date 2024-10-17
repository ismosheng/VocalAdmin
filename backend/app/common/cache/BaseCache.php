<?php
declare(strict_types=1);

namespace app\common\cache;

use think\Cache;
use think\facade\Log;

abstract class BaseCache extends Cache
{

    protected $tagName;

    public function __construct()
    {
        parent::__construct(app());
        $this->tagName = get_class($this);
    }

    
    public function set($key, $value, $ttl = null): bool
    {
        return $this->store()->tag($this->tagName)->set($key, $value, $ttl);
    }


    public function deleteTag(): bool
    {
        return $this->tag($this->tagName)->clear();
    }
}
