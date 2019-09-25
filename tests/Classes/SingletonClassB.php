<?php
/**
 * Author: 沧澜
 * Date: 2019-09-25
 */

namespace CalContainer\Tests\Classes;


class SingletonClassB
{
    private static $instance;
    
    
    private function __construct()
    {
    }
    
    /**
     * @return mixed
     */
    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new static();
    }
}