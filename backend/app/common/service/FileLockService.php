<?php
namespace app\common\service;

/**
 * 文件锁：用于处理并发访问
 *
 * @Author SentinelPrime
 * @DateTime 2024-09-25
 */
class FileLockService
{
    private $lockDir;
    private $lockFile;

    public function __construct($lockDir='./../runtime/filelock')
    {
        $this->lockDir = $lockDir;
    }

    /**
     * 文件锁
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-25
     * @param string $key
     * @return boolean
     */
    public function lock(string $key): bool
    {
        $lockFile = $this->lockFile = $this->lockDir. '/'. md5($key). '.lock';
        if (file_exists($lockFile)) {
            return_error('并发访问受限，请稍后再试');
        }
        if(!is_dir($this->lockDir)){
            mkdir($this->lockDir, 0777, true);
        }
        touch($lockFile);
        return true;
    }

    /**
     * 解锁
     *
     * @Author SentinelPrime
     * @DateTime 2024-09-25
     * @return boolean
     */
    public function unlock(): bool
    {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
            return true;
        }
        return false; // 文件未被锁定
    }
}