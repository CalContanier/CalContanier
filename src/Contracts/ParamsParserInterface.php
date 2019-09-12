<?php
/**
 * Author: 沧澜
 * Date: 2019-09-12
 */

namespace CalContainer\Contracts;


interface ParamsParserInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function parse(array $params);
    
    /**
     * @param mixed $abstract
     * @return mixed
     */
    public function get($abstract);
    
}