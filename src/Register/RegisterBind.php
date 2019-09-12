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
     * @param mixed $instance
     * @return $this
     */
    public function bind(string $abstract, $instance)
    {
        $this->binding[$abstract] = $instance;
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