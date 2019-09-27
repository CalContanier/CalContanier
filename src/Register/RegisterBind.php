<?php
/**
 * Author: 沧澜
 * Date: 2019-07-29
 */

namespace CalContainer\Register;


use CalContainer\Contracts\RegisterInterface;
use Closure;

class RegisterBind implements RegisterInterface
{
    /**
     * @var array
     */
    protected $binds = [];
    
    /**
     * @var array
     */
    protected $creates = [];
    
    /**
     * @var array
     */
    protected $caches = [];
    
    /**
     * @param string $abstract
     * @param object|Closure|mixed $instance
     * @return $this
     */
    public function bind($abstract, $instance = null)
    {
        if (is_object($abstract)) {
            $this->binds[get_class($abstract)] = $abstract;
        } else {
            $this->binds[$abstract] = $instance;
        }
        return $this;
    }
    
    /**
     * @param string $abstract
     * @param Closure $instance
     * @return RegisterBind
     */
    public function delay(string $abstract, Closure $instance)
    {
        $this->binds[$abstract] = $instance;
        return $this;
    }
    
    /**
     * @param string $abstract
     * @param object|Closure|mixed $instance
     * @return RegisterBind
     */
    public function create(string $abstract, $instance = null)
    {
        if (is_object($abstract)) {
            $this->creates[get_class($abstract)] = $abstract;
        } else {
            $this->creates[$abstract] = $instance;
        }
        return $this;
    }
    
    /**
     * @param mixed $abstract
     * @return bool
     */
    public function has($abstract)
    {
        return isset($this->binds[$abstract]);
    }
    
    /**
     * @param mixed $abstract
     * @return mixed|null
     */
    public function get($abstract)
    {
        if (isset($this->caches[$abstract])) {
            return $this->caches[$abstract];
        } elseif (isset($this->binds[$abstract])){
            return $this->caches[$abstract] = $this->getRegisterInstance($this->binds[$abstract]);
        } else if (isset($this->creates[$abstract])) {
            return $this->getRegisterInstance($this->creates[$abstract]);
        } else {
            return null;
        }
    }
    
    /*---------------------------------------------- protocted ----------------------------------------------*/
    
    /**
     * @param object|Closure|string|mixed $instance
     * @return mixed
     */
    protected function getRegisterInstance($instance)
    {
        if (is_callable($instance)) {
            return call_user_func($instance);
        } elseif (is_string($instance) && class_exists($instance)) {
            return new $instance();
        } else {
            return $instance;
        }
    }
    
    /*---------------------------------------------- get ----------------------------------------------*/
    
    /**
     * @return array
     */
    public function getBinds(): array
    {
        return $this->binds;
    }
    
    /**
     * @return array
     */
    public function getCaches(): array
    {
        return $this->caches;
    }
    
    /**
     * @return array
     */
    public function getCreates(): array
    {
        return $this->creates;
    }
    
}