<?php
/**
 * Author: 沧澜
 * Date: 2019-09-24
 */

namespace CalContainer\Contracts;

interface ParamParserInterface
{
    /**
     * @param string $class
     * @param string $keyName
     * @param mixed $default
     * @return mixed
     */
    public function getObject(string $class, string $keyName, $default = null);
    
    /**
     * @param string $type
     * @param string $keyName
     * @param mixed $default
     * @return mixed
     */
    public function getValue(string $type, string $keyName, $default = null);
    
    /**
     * @param array $params
     * @return mixed
     */
    public function parse(array $params);
    
}