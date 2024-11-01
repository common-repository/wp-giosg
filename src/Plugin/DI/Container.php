<?php

namespace WPGiosg\Plugin\DI;

class Container implements \ArrayAccess
{
   private $services = [];
   private $values = [];

    /**
     * Constructor
     */
   public function __construct()
   {
   }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
   public function registerService($name, $value)
   {
       $this->services[$name] = $value;
   }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]) || isset($this->services[$offset]);
    }

    public function offsetGet($offset)
    {
        if (isset($this->values[$offset])) {
            if ($this->values[$offset] instanceof \Closure) {
                $this->values[$offset] = $this->values[$offset]($this);
            }
            return $this->values[$offset];
        } elseif (isset($this->services[$offset])) {
            return $this->services[$offset]($this);
        }
    }

    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->values[$offset])) {
            unset($this->values[$offset]);
        } else {
            unset($this->services[$offset]);
        }
    }
}
