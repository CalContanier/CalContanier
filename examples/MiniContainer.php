<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-06
 * Annotation:
 */

namespace CalContainer\Examples;

use Closure;
use Exception;
use phpDocumentor\Reflection\Types\Callable_;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class MiniContainer
{
    const PARAM_DEF = [
        'string' => '',
        'int' => 0,
        'array' => [],
        'bool' => false,
        'float' => 0.0,
        'iterable' => []
    ];
    /**
     * create a new object
     * @param string $abstract
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public function create($abstract)
    {
        $refClass = new ReflectionClass($abstract);
        if ($refClass->getName() === Closure::class || $refClass->getName() === Callable_::class) {
            return function (){};
        } elseif ($refClass->hasMethod('__construct')) {
            $methodParams = $refClass->getMethod('__construct')->getParameters();
            foreach ($methodParams as $param) {
                if ($param->getClass()) {
                    $_constructParams[] = $this->create($param->getClass()->getName());
                } elseif ($param->isDefaultValueAvailable()) {
                    $_constructParams[] = $param->getDefaultValue();
                } elseif ($param->getType()) {
                    $_constructParams[] = ([
                        'string' => '',
                        'int' => 0,
                        'array' => [],
                        'bool' => false,
                        'float' => 0.0,
                        'iterable' => [],
                        'callable' => function() {}
                    ])[$param->getType()->getName()] ?? null;
                } else {
                    $_constructParams[] = null;
                }
            }
        }
        return $refClass->newInstance(... ($_constructParams ?? []));
    }
    
}