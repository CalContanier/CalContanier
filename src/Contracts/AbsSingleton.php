<?php
/**
 * Author: 沧澜
 * Date: 2019-09-09
 */

namespace CalContainer\Contracts;

/**
 * Class AbsSingleton
 * @package CalContainer\Contracts
 */
abstract class AbsSingleton
{
    /**
     * singleton list
     * @var array
     */
    private static $instances = [];
    
    // to private
    private function __clone() { }
    private function __construct() {
        $this->init();
    }
    // init
    protected function init() {}
    
    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!$instance = &self::$instances[static::class]) {
            $instance = new static();
        }
        return $instance;
    }
    
}