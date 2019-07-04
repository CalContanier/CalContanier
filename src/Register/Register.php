<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Register;

use CalContainer\Contracts\RegisterInterface;

class Register implements RegisterInterface
{
    /**
     * @var []static
     */
    private static $registers;
    
    /**
     * @var array
     */
    protected $instance = [];
    
    /**
     * @param $abstract
     * @param $instance
     * @return $this
     */
    public function register($abstract, $instance)
    {
        $this->instance[$abstract] = $instance;
        return $this;
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->instance[$id];
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->instance[$id]);
    }
    
    /*---------------------------------------------- Singleton ----------------------------------------------*/
    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!$instance = &self::$registers[static::class]) {
            $instance = new static();
        }
        return $instance;
        
    }
    private function __construct() { }
    private function __clone() { }
}