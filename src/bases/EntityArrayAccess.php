<?php


namespace App\bases;


class EntityArrayAccess implements \ArrayAccess
{

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {


        $method = 'get' . $this->normalizeName($offset);
        if (method_exists($this, $method)) {
            return true;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        $method = 'get' . $this->normalizeName($offset);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $method = 'set' . $this->normalizeName($offset);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {

    }

    protected function normalizeName($name)
    {
        $explodeOffset = explode('_', $name);
        foreach ($explodeOffset as $k => $v) {
            $explodeOffset[$k] = ucfirst($v);
        }
        return implode('', $explodeOffset);
    }
}