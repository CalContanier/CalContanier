<?php
/**
 * Author: 沧澜
 * Date: 2019-07-29
 */

namespace CalContainer\Register;


use CalContainer\Contracts\RegisterInterface;

class RegisterBind implements RegisterInterface
{
    /**
     * @var array
     */
    protected $binding = [];
    
    /**
     * @var array
     */
    protected $instanceCaches = [];
    
    /**
     * @param string $abstract
     * @param object|mixed $instance
     * @return $this
     */
    public function bind($abstract, $instance = null)
    {
        if (is_object($abstract)) {
            $this->binding[get_class($abstract)] = $abstract;
        } else {
            $this->binding[$abstract] = $instance;
        }
        return $this;
    }
    
    /**
     * @param string $abstract
     * @param $instance
     */
    public function delay(string $abstract, $instance)
    {
    
    }
    
    /**
     * @param mixed $abstract
     * @return bool
     */
    public function has($abstract)
    {
        return isset($this->binding[$abstract]);
    }
    
    /**
     * @param mixed $abstract
     * @return mixed|null
     */
    public function get($abstract)
    {
        return $this->binding[$abstract] ?? null;
    }
    
    /**
     * @return array
     */
    public function getBind(): array
    {
        return $this->binding;
    }
    
    
}