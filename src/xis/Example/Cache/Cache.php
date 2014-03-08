<?php
namespace xis\Example\Cache;

interface Cache
{
    /**
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return null
     */
    public function set($key, $value);

    /**
     * @param $key
     * @return null
     */
    public function remove($key);

    /**
     * @return null
     */
    public function clear();
}