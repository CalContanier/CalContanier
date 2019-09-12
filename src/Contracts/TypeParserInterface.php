<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Contracts;


interface TypeParserInterface
{
    /**
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    public function default(string $type, $default = null);
    
    /**
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    public function set(string $type, $default);
    
    /**
     * @param array $typeMap
     * @return mixed
     */
    public function setByArray(array $typeMap);
}