<?php

namespace App\bases;

class Container extends \stdClass implements \ArrayAccess
{
    /** @var array */
    private static $data = [];
    private static $container = null;

    private function __construct()
    {

    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }

    /**
     * @return Container
     */
    public static function getInstance()
    {
        if (is_null(self::$container)) {
            self::$container = new self();
        }

        return self::$container;
    }


    /**
     * @param $offset
     * @param $value
     */
    public function set($offset, $value)
    {
        self::$data[$offset] = $value;
    }

    /**
     * @param $offset
     * @return |null
     */
    public function get($offset)
    {
        return self::$data[$offset] ?: null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return (bool) array_key_exists($offset, self::$data);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return self::$data[$offset] ?: null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return $this|void
     */
    public function offsetSet($offset, $value)
    {
        self::$data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (self::$data[$offset]) {
            unset(self::$data[$offset]);
        }
    }
}