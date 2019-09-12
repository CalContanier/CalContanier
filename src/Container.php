<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer;

use CalContainer\Components\TypeParser;
use CalContainer\Contracts\AbsSingleton;
use CalContainer\Contracts\TypeParserInterface;
use CalContainer\Exceptions\ContainerException;
use CalContainer\Register\Register;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionParameter;

class Container extends AbsSingleton
{
    
    /**
     * @var Register
     */
    protected $register;
    
    /**
     * @var TypeParserInterface
     */
    protected $typeParser;
    
    /**
     * singleton init
     */
    protected function init()
    {
        $this->register = Register::getInstance();
        $this->typeParser = new TypeParser();
    }
    
    /**
     * @return Register
     */
    public static function register()
    {
        return static::getInstance()->register;
    }
    
    /**
     * @param $abstract
     * @param string $method
     * @param array $runParams
     * @param int $options
     * @return mixed
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function call($abstract, $method, array $runParams = [], int $options = 0)
    {
        $refClass = new ReflectionClass($abstract);
        $refClass->hasMethod($method) || ContainerException::throw("Uncaught Error: Call to undefined method " . $refClass->getName() . "::$method .");;
        ($reflectionMethod = $refClass->getMethod($method))->isPublic() || ContainerException::throw("$method must be public.");
        return $abstract->{$method}(... $this->getMethodParams($reflectionMethod, $refClass, $method, $runParams, $options));
    }
    
    /**
     * @param Closure $callable
     * @param array $runParams
     * @param int $options
     * @return mixed
     * @throws ReflectionException
     */
    public function callCallable(Closure $callable, array $runParams = [], int $options = 0)
    {
        return call_user_func_array($callable, $this->getMethodParams(new ReflectionFunction($callable), new ReflectionClass($callable), '', $runParams, $options));
    }
    
    /**
     * @param string|object|ReflectionClass $abstract
     * @return mixed
     * @throws ReflectionException
     * @throws Exception
     */
    public function get($abstract)
    {
        $refClass = $abstract instanceof ReflectionClass ? $abstract : new ReflectionClass($abstract);
        
        // get in bind
        if (($bind = $this->register->bind())->has($refClass->getName())) {
            return $bind->get($refClass->getName());
        }
        
        // to create
        return $this->create($abstract);
    }
    
    
    /**
     * create a new object
     * @param string $abstract
     * @param array $params
     * @param int $options
     * @return mixed
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function create($abstract, array $params = [], int $options = 0)
    {
        return $this->createInstance($abstract, $params);
    }
    
    /**
     * create new instance
     * @param string|ReflectionClass $abstract class
     * @param array $runParams for the first level only
     * @param int $options
     * @param array $tmp temporary class object
     * @return object
     * @throws ContainerException
     * @throws ReflectionException
     */
    protected function createInstance($abstract, array $runParams = [], int $options = 0, array $tmp = [])
    {
        $className = $abstract instanceof ReflectionClass ? $abstract->getName() : $abstract;
        // check: $tmp[$className] is not null
        if (isset($tmp[$className])) {
            return $tmp[$className];
        }
        array_key_exists($className, $tmp) && ContainerException::throw('can not create a circular dependencies class object.');
        // create: tmp([ $className => &null])
        $instance = &$tmp[$className];
        
        $refClass = $abstract instanceof ReflectionClass ? $abstract : new ReflectionClass($abstract);
        if ($refClass->isInterface() || $refClass->isAbstract()) {
            ContainerException::throw('can not create a class in interface or abstract.');
        } elseif ($refClass->getName() === Closure::class) {
            return function () { };
        }
        if ($refClass->hasMethod('__construct')) {
            $reflectionMethod = $refClass->getMethod('__construct');
            $reflectionMethod->isPublic() || ContainerException::throw("can not automatically create the class object, __construct must be public.");
            $_constructParams = $this->getMethodParams($reflectionMethod, $refClass, '__construct', $runParams, $options);
        }
        
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
            return $this->typeParser->default($param->getType()->getName());
        } else {
            return null;
        }
    }
    
    /**
     * get method params
     * @param ReflectionFunctionAbstract $refMethod
     * @param ReflectionClass $refClass
     * @param string $method
     * @param array $runParams
     * @param int $options
     * @return array
     */
    private function getMethodParams(ReflectionFunctionAbstract $refMethod, ReflectionClass $refClass, string $method, array $runParams = [], int $options = 0)
    {
        
        
        
        return array_map(function (ReflectionParameter $param) use ($refClass, $method, $runParams, $options, &$refMap) {
            // get in $params
            array_map(function () {
            
            }, $runParams);
            
            // get in contact and bind or create new instance
            if (($result = $this->paramsHandle($param)) instanceof ReflectionClass) {
                $instance = $this->register->contact()->getInAll($param->getClass()->getName(), $refClass->getNamespaceName(), $refClass->getName(), $method) ?? $this->get($result);
                if (is_string($instance) && class_exists($instance)) {
                    return $refMap[$instance] ?? ($refMap[$instance] = $this->create($instance));
                } else {
                    return $instance;
                }
            } else {
                return $result;
            }
        }, $refMethod->getParameters());
    }
    
}