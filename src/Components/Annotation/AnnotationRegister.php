<?php
/**
 * Author: 沧澜
 * Date: 2020-09-17
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Constants\Constant;
use CalContainer\Contracts\AnnotationInterface;
use CalContainer\Exceptions\RegisterException;
use CalJect\Productivity\Components\Criteria\Criteria;
use CalJect\Productivity\Exceptions\ClosureRunException;

/**
 * Class AnnotationRegister
 * @package CalContainer\Components\Annotation
 */
class AnnotationRegister
{
    /**
     * @var array
     */
    protected $annotations;

    /**
     * @param AnnotationInterface $annotation
     * @return $this
     * @throws ClosureRunException
     */
    public function register(AnnotationInterface $annotation)
    {
        Criteria::opts($annotation->scope() ?: Constant::SCOPE_ALL)
            ->binds([Constant::SCOPE_ALL, Constant::SCOPE_CLASS, Constant::SCOPE_FUNCTION], function ($data, $opt) use ($annotation) {
                $this->annotations[$opt][$annotation->name()] = $annotation;
            })->handle();
        return $this;
    }

    /**
     * @param array $annotations
     * @return $this
     * @throws ClosureRunException
     * @throws RegisterException
     */
    public function registers(array $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof AnnotationInterface) {
                (is_string($annotation) && class_exists($annotation) || RegisterException::throw('annotation class can not found.'))
                    && $annotation = new $annotation();
                $annotation instanceof AnnotationInterface ?: $this->register($annotation);
            } else {
              throw RegisterException::throw('annotation is not instanceof AnnotationInterface.');
            }
        }
        return $this;
    }

    /**
     * @param AnnotationInterface|string $name
     * @param int|null $scope
     * @return bool|AnnotationInterface
     */
    public function get($name, $scope = null)
    {
        $name instanceof AnnotationInterface && $name = $name->name();
        return $this->annotations[$scope ?: Constant::SCOPE_ALL][$name] ?? false;
    }

    /**
     * @param $name
     * @param int|null $scope
     * @return bool
     */
    public function has($name, $scope = null)
    {
        return (bool)$this->get($name, $scope);
    }


}