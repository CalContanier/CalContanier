<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer;


use CalContainer\Exceptions\InstanceCreateException;
use CalContainer\Register\RegisterManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    /**
     * @var []static
     */
    private static $containers;
    
    /**
     * @var RegisterManager
     */
    protected $registerManager;
    
    protected function init()
    {
        $this->registerManager = new RegisterManager();
    }
    
    /**
     * @param $abstract
     * @return object
     * @throws InstanceCreateException
     * @throws ReflectionException
     */
    public function make($abstract)
    {
        return $this->create($abstract);
        
    }
    
    public function call()
    {
    
    }
    
    /**
     * create a new object
     * @param string $abstract
     * @return object
     * @throws ReflectionException
     * @throws InstanceCreateException
     */
    protected function create($abstract)
    {
        if (!class_exists($abstract)) {
            InstanceCreateException::throw($abstract . ' class is not exists.');
        }
        $refClass = new ReflectionClass($abstract);
        if ($refClass->hasMethod('__construct')) {
            $constructMethod = $refClass->getMethod('__construct');
            if ($constructMethod->isPublic()) {
                $consParams = $constructMethod->getParameters();
                foreach ($consParams as $param) {
                    $className = $param->getClass()->getName();
                    if ($this->has($className)) {
                        $_constructParams[] = $this->get($className);
                    } else {
                        $_constructParams[] = $this->create($className);
                    }
                }
            } else {
                InstanceCreateException::throw('can not create ' . $abstract . ': Call to ' . $constructMethod->getPrototype() . ' __construct');
            }
        }
        return $refClass->newInstance(... ($_constructParams ?? []));
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
        return $this->registerManager->get($id);
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
        return $this->registerManager->has($id);
    }
    
    /**
     * @return RegisterManager
     */
    public static function register(): RegisterManager
    {
        return self::getInstance()->registerManager;
    }
    
    /*---------------------------------------------- Singleton ----------------------------------------------*/
    
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
    private function __construct() { $this->init(); }
    private function __clone() { }
    
}