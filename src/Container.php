<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer;

use CalContainer\Components\ParamParser;
use CalContainer\Components\TypeParser;
use CalContainer\Contracts\AbsSingleton;
use CalContainer\Contracts\ParamParserInterface;
use CalContainer\Contracts\TypeParserInterface;
use CalContainer\Exceptions\ContainerException;
use CalContainer\Register\Register;
use CalContainer\Register\RegisterBind;
use CalContainer\Register\RegisterContact;
use Closure;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionParameter;

class Container extends AbsSingleton implements ContainerInterface
{
    
    /**
     * @var TypeParserInterface
     */
    protected $typeParser;
    
    /**
     * ParamParserInterface class
     * @var string
     */
    protected $paramsParserClass;
    
    /**
     * singleton init
     */
    protected function init()
    {
        $this->typeParser = new TypeParser();
    }
    
    /**
     * @return Register
     */
    public static function register()
    {
        return Register::getInstance();
    }
    
    /**
     * @return RegisterContact
     */
    public static function contactRegister()
    {
        return Register::contact();
    }
    
    /**
     * @return RegisterBind
     */
    public static function bindRegister()
    {
        return Register::bind();
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
        return Register::bind()->has($id);
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
        if (Register::bind()->has($refClass->getName())) {
            return Register::bind()->get($refClass->getName());
        }
        return $this->create($refClass);
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
        return $this->createInstance($abstract, $params, $options);
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
            if ($reflectionMethod->isPublic()) {
                $_constructParams = $this->getMethodParams($reflectionMethod, $refClass, '__construct', $runParams, $options);
            } else if ($refClass->hasMethod('getInstance')) {
                $reflectionMethod = $refClass->getMethod('getInstance');
                if ($reflectionMethod->isPublic() && $reflectionMethod->isStatic()) {
                    $_constructParams = $this->getMethodParams($reflectionMethod, $refClass, 'getInstance', $runParams, $options);
                    return $instance = $refClass->getName()::getInstance(... $_constructParams);
                }
            } else {
                ContainerException::throw("can not automatically create the class object, __construct must be public.");
            }
        }
        
        return $instance = $refClass->newInstance(... ($_constructParams ?? []));
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
    private function getMethodParams(ReflectionFunctionAbstract $refMethod, ReflectionClass $refClass, string $method, array $runParams = [], int $options = 0): array
    {
        if(!$methodParameters = $refMethod->getParameters()) {
            return [];
        }
        if ($this->paramsParserClass && is_subclass_of($this->paramsParserClass, ParamParserInterface::class)) {
            $paramParser = new $this->paramsParserClass();
        } else {
            $paramParser = new ParamParser();
        }
        $paramParser->parse($runParams);
        return array_map(function (ReflectionParameter $param) use ($refClass, $method, $options, &$refMap, $paramParser) {
            $defaultValue = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
            if (($class = $param->getClass()) && $class instanceof ReflectionClass) {
                $instance = $paramParser->getObject($class->getName(), $param->getName(), function () use ($class, $refClass, $method) {
                    return Register::contact()->getInAll($class->getName(), $refClass->getNamespaceName(), $refClass->getName(), $method, function () use ($class) {
                        return $this->get($class);
                    });
                });
                if (is_string($instance) && class_exists($instance)) {
                    return $refMap[$instance] ?? $refMap[$instance] = $this->create($instance);
                } else {
                    return $instance;
                }
            } else if ($param->getType()) {
                return $paramParser->getValue($param->getType(), $param->getName(), $this->typeParser->default($param->getType()->getName(), $defaultValue));
            } else {
                return $defaultValue;
            }
        }, $methodParameters) ?? [];
    }
    
    /*---------------------------------------------- set ----------------------------------------------*/
    
    /**
     * @param string $paramsParserClass
     * @return $this
     */
    public function paramParser(string $paramsParserClass)
    {
        $this->paramsParserClass = $paramsParserClass;
        return $this;
    }
    
}