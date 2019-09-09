<?php
/**
 * Author: 沧澜
 * Date: 2019-08-15
 */

namespace CalContainer\Components;


class ArgsParser
{
    /**
     * @var array
     */
    protected $contact = [];
    
    /**
     * @var array
     */
    protected $object = [];
    
    /**
     * @var array
     */
    protected $base = [];
    
    /**
     * @var array
     */
    protected $args = [];
    
    /**
     * ArgsParser constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
        $this->init($args);
    }
    
    /**
     * @param array $args
     */
    protected function init(array $args)
    {
        foreach ($args as $key => $arg) {
            if (is_object($args)) {
                if (class_exists($key)) {
                    $this->contact['obj'][$key] = $arg;
                } else {
                    $this->object[] = $arg;
                }
            } else if (is_string($key)) {
                $this->contact['base'][$key] = $arg;
            } else {
                $this->base[gettype($arg)][] = $arg;
            }
        }
    }
    
    /**
     * @param array $args
     * @return static
     */
    public static function parse(array $args)
    {
        return new static($args);
    }
    
    /**
     * @param mixed $default
     * @return mixed
     */
    public function popObj($default = null)
    {
        return array_shift($this->object) ?? $default;
    }
    
    /**
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    public function popBase(string $type, $default = null)
    {
        return array_shift($arr = &$this->base[$type]) ?? $default;
    }
    
    /**
     * @param string $class
     * @param mixed $default
     * @return mixed
     */
    public function contactObj(string $class, $default = null)
    {
        return $this->contact['obj'][$class] ?? $default;
    }
    
    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function contactBase(string $name, $default = null)
    {
        return $this->contact['base'][$name] ?? $default;
    }
    
    
}