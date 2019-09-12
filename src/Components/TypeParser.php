<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Components;


use CalContainer\Contracts\TypeParserInterface;

class TypeParser implements TypeParserInterface
{
    /**
     * @var array
     */
    protected $typeParsers = [
        'string' => '',
        'int' => 0,
        'array' => [],
        'bool' => false,
        'float' => 0.0,
        'iterable' => [],
    ];
    
    /**
     * TypeParser constructor.
     */
    public function __construct()
    {
        $this->set('callable', function () {});
    }
    
    /**
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    public function default(string $type, $default = null)
    {
        return $this->typeParsers[$type] ?? $default;
    }
    
    /**
     * @param string $type
     * @param mixed $default
     * @return $this
     */
    public function set(string $type, $default)
    {
        $this->typeParsers[$type] = $default;
        return $this;
    }
    
    /**
     * @param array $typeMap
     * @return $this
     */
    public function setByArray(array $typeMap)
    {
        $this->typeParsers = $typeMap + $this->typeParsers;
        return $this;
    }
}