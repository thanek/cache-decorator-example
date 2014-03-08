<?php
namespace xis\Example\Cache;

class SimpleFileCache implements Cache
{
    private $cacheDirectory;

    /**
     * @param string $cacheDirectory
     */
    public function __construct($cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $keyHash = $this->getFilename($key);
        return file_exists($keyHash);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $filePath = $this->getFilename($key);
        $content = file_get_contents($filePath);
        return unserialize($content);
    }

    /**
     * @param $key
     * @param $value
     * @return null
     */
    public function set($key, $value)
    {
        $filePath = $this->getFilename($key);
        file_put_contents($filePath, serialize($value));
    }

    /**
     * @param $key
     * @return null
     */
    public function remove($key)
    {
        $filePath = $this->getFilename($key);
        @unlink($filePath);
    }

    /**
     * @return null
     */
    public function clear()
    {
        $files = scandir($this->cacheDirectory);
        foreach ($files as $file) {
            if (in_array($file, array('.', '..'))) {
                continue;
            }
            unlink($this->cacheDirectory . '/' . $file);
        }
    }

    /**
     * @param $key
     * @return string
     */
    private function getFilename($key)
    {
        return $this->cacheDirectory . '/' . md5($key);
    }
}