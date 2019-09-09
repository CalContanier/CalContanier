<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer;

use CalContainer\Contracts\AbsSingleton;
use CalContainer\Exceptions\ContainerException;
use CalContainer\Register\Register;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class Container extends AbsSingleton
{
    
    /**
     * @var Register
     */
    protected $register;
    
    /**
     * singleton init
     */
    protected function init()
    {
        $this->register = Register::getInstance();
    }
    
    /**
     * @return Register
     */
    public static function register()
    {
        return static::getInstance()->register;
    }
    
    /**
     * @param object/string $abstract
     * @param string $method
     * @param array $runParams
     * @return mixed
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function call($abstract, $method, array $runParams = [])
    {
        $refClass = new ReflectionClass($abstract);
        $refClass->hasMethod($method) || ContainerException::throw("Uncaught Error: Call to undefined method " . $refClass->getName() . "::$method .");;
        $reflectionMethod = $refClass->getMethod($method);
        $reflectionMethod->isPublic() || ContainerException::throw("$method must be public.");
        $params = $this->getMethodParams($reflectionMethod, $refClass, $method, $runParams);
        return $abstract->{$method}(... $params);
    }
    
    /**
     * @param string|object|ReflectionClass $abstract
     * @return mixed|null
     * @throws ReflectionException
     * @throws Exception
     */
    public function get($abstract)
    {
        $refClass = $abstract instanceof ReflectionClass ? $abstract : new ReflectionClass($abstract);
        
        // get in bind
        $bind = $this->register->bind();
        if ($bind->has($refClass->getName())) {
            return $bind->get($refClass->getName());
        }
        
        // to create
        return $this->create($abstract);
    }
    
    
    /**
     * create a new object
     * @param string $abstract
     * @param array $params
     * @return Closure|object
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function create($abstract, array $params = [])
    {
        return $this->createInstance($abstract, $params);
    }
    
    /**
     * create new instance
     * @param string|ReflectionClass $abstract class
     * @param array $runParams for the first level only
     * @param array $tmp temporary class object
     * @return object
     * @throws ReflectionException
     * @throws ContainerException
     */
    protected function createInstance($abstract, array $runParams = [], array $tmp = [])
    {
        $className = $abstract instanceof ReflectionClass ? $abstract->getName() : $abstract;
        // todo: flag 1: check
        if (isset($tmp[$className])) {
            return $tmp[$className];
        }
        array_key_exists($className, $tmp) && ContainerException::throw('can not create a circular dependencies class object.');
        $instance = &$tmp[$className];
        
        // todo: flag 2: get method && parse params
        $refClass = $abstract instanceof ReflectionClass ? $abstract : new ReflectionClass($abstract);
        if ($refClass->isInterface() || $refClass->isAbstract()) {
            ContainerException::throw('can not create a class in interface or abstract.');
        } elseif ($refClass->getName() === Closure::class) {
            return function () { };
        }
        if ($refClass->hasMethod('__construct')) {
            $reflectionMethod = $refClass->getMethod('__construct');
            $reflectionMethod->isPublic() || ContainerException::throw("can not automatically create the class object, __construct must be public.");
            $_constructParams = $this->getMethodParams($reflectionMethod, $refClass, '__construct', $runParams);
        }
        
        // todo: flag 3: make instance && bind to tmp
        return $instance = $refClass->newInstance(... ($_constructParams ?? []));
    }
    
    /**
     * parse
     * @param ReflectionParameter $param
     * @return mixed|ReflectionClass|null
     * @throws ReflectionException
     */
    private function paramsHandle(ReflectionParameter $param)
    {
        if ($param->getClass()) {
            return $param->getClass();
        } elseif ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        } elseif ($param->getType()) {
            return [
                'string' => '',
                'int' => 0,
                'array' => [],
                'bool' => false,
                'float' => 0.0,
                'iterable' => [],
                'callable' => function () {}
            ][$param->getType()->getName()] ?? null;
        } else {
            return null;
        }
    }
    
    /**
     * get method params
     * @param ReflectionMethod $reflectionMethod
     * @param ReflectionClass $refClass
     * @param string $method
     * @param array $runParams
     * @return array
     */
    private function getMethodParams(ReflectionMethod $reflectionMethod, ReflectionClass $refClass, string $method, array $runParams = [])
    {
        return array_map(function (ReflectionParameter $param) use ($refClass, $method, $runParams) {
            // get in $params
            
            // get in contact and bind or create new instance
            if (($result = $this->paramsHandle($param)) instanceof ReflectionClass) {
                return $this->register->contact()->getInAll($param->getClass()->getName(), $refClass->getNamespaceName(), $refClass->getName(), $method) ?? $this->get($result);
            } else {
                return $result;
            }
        }, $reflectionMethod->getParameters());
    }
    
}