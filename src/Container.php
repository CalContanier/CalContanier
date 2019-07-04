<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer;


use CalContainer\Register\RegisterManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

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
        // TODO: Implement get() method.
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
        // TODO: Implement has() method.
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
    private function __construct() { }
    private function __clone() { }
    
}