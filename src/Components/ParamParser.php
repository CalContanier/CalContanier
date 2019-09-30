<?php
/**
 * Author: 沧澜
 * Date: 2019-09-24
 */

namespace CalContainer\Components;


use CalContainer\Contracts\ParamParserInterface;
use CalContainer\Utils\ClosureUtil;

class ParamParser implements ParamParserInterface
{
    
    /**
     * [
     *  "contact" => [
     *      "$key" => $value
     *  ],
     *  "list" => [
     *      "$class" => $object
     *  ]
     * ]
     * @var array
     */
    protected $objects = [];
    
    /**
     * [
     *  "contact" => [
     *      "$key" => $value
     *  ],
     *  "list" => [
     *      "string" => [
     *          "$value",
     *          "$value"
     *      ],
     *      "int" => [
     *          $value,
     *          $value
     *      ],
     *      ...
     *  ]
     * ]
     * @var array
     */
    protected $values = [];
    
    
    /**
     * @param string $class
     * @param string $keyName
     * @param mixed $default
     * @return mixed
     */
    public function getObject(string $class, string $keyName, $default = null)
    {
        if (isset($this->objects['contact']) && $contact = &$this->objects['contact']) {
            $value = $contact[$keyName] ?? $contact[$class] ?? null;
        } else if (isset($this->objects['list']) && $list = &$this->objects['list']) {
            $value = $list[$keyName] ?? $list[$class] ?? null;
        }
        return $value ?? ClosureUtil::checkRun($default);
    }
    
    /**
     * @param string $type
     * @param string $keyName
     * @param mixed $default
     * @return mixed
     */
    public function getValue(string $type, string $keyName, $default = null)
    {
        if (isset($this->values['contact']) && $contact = $this->values['contact']) {
            $value = $contact[$keyName] ?? null;
        } else if (isset($this->values['list']) && $list = &$this->values['list']) {
            if (isset($list[$type]) && $types = &$list[$type]) {
                $value = array_shift($types);
            }
        }
        return $value ?? ClosureUtil::checkRun($default);
    }
    
    /**
     * @param array $params
     * @return $this
     */
    public function parse(array $params)
    {
        foreach ($params as $key => $value) {
            (is_object($value) || class_exists($value)) ? $this->objectSet($key, $value) : $this->valueSet($key, $value);
        }
        return $this;
    }
    
    /**
     * @param int|string $key
     * @param object $value
     */
    protected function objectSet($key, $value)
    {
        if (is_string($key)) {
            $this->objects['contact'][$key] = $value;
        } else if (is_object($value)){
            $subs = class_implements($value) + class_parents($value);
            $this->objects['list'][get_class($value)] = $value;
            foreach ($subs as $name) {
                $this->objects['list'][$name] = $value;
            }
        } else {
            $this->objects['list'][$value] = $value;
        }
    }
    
    
    /**
     * @param int|string $key
     * @param mixed $value
     */
    protected function valueSet($key, $value)
    {
        if (is_string($key)) {
            $this->values['contact'][$key] = $value;
        } else {
            if(($type = gettype($value)) == 'integer') {
                $this->values['list']['int'][] = $value;
            } else {
                $this->values['list'][$type][] = $value;
            }
        }
    }
    
}