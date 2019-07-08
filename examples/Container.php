<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Examples;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Container implements ContainerInterface
{
    /**
     * @var []static
     */
    private static $containers;
    
    /**
     * @var array
     */
    protected $instances = [];
    
    private function __construct() { }
    private function __clone() { }
    
    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!$instance = &self::$containers[static::class]) {
            $instance = new static();
        }
        return $instance;
    }
    
    /**
     * @param $abstract
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public function make($abstract)
    {
        return $this->create($abstract);
    }
    
    /**
     * create a new object
     * @param string $abstract
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    protected function create($abstract)
    {
        $refClass = new ReflectionClass($abstract);
        if ($refClass->hasMethod('__construct')) {
            $methodParams = $refClass->getMethod('__construct')->getParameters();
            foreach ($methodParams as $param) {
                $_constructParams[] = $this->create($param->getClass()->getName());
            }
        }
        return $refClass->newInstance(... ($_constructParams ?? []));
    }
    
    /**
     * @param $abstract
     * @param $method
     * @return mixed
     * @throws ReflectionException
     */
    public function call($abstract, $method)
    {
        $instance = is_object($abstract) ? $abstract : $this->create($abstract);
        $refMethod = new ReflectionMethod($instance, $method);
        foreach ($refMethod->getParameters() as $param) {
            $_methodParams[] = $this->create($param->getClass()->getName());
        }
        return $instance->{$method}(...($_methodParams ?? []));
    }
    
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->instances[$id];
        }
        throw new class('No entry was found for **this** identifier') extends Exception implements NotFoundExceptionInterface{ };
    }
    
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }
    
}