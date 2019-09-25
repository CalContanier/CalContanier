<?php
/**
 * Author: 沧澜
 * Date: 2019-09-25
 */

namespace CalContainer\Tests\Classes;


class SingletonClassA
{
    
    
    
    private function __construct()
    {
    }
    
    /**
     * @return SingletonClassA
     */
    public static function getInstance()
    {
        return new static();
    }
    
}