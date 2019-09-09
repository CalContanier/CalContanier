<?php
/**
 * Author: 沧澜
 * Date: 2019-07-29
 */

namespace CalContainer\Register;


class RegisterBind
{
    /**
     * @var array
     */
    protected $binding = [];
    
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
     * @return bool
     */
    public function has(string $abstract)
    {
        return isset($this->binding[$abstract]);
    }
    
    /**
     * @param string $abstract
     * @return mixed|null
     */
    public function get(string $abstract)
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