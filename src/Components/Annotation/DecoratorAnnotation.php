<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AbsAnnotationParseInterface;

/**
 * Class DecoratorAnnotation
 * @package CalContainer\Components\Annotation
 */
class DecoratorAnnotation extends AbsAnnotationParseInterface
{
    
    /**
     * 装饰器
     * @var array
     */
    protected $decorators = [];

    /**
     * @param string $docComment
     * @begin DataProviderAnnotation::begin
     * @return mixed
     */
    protected function doParse(string $docComment)
    {
        return [$docComment];
    }

}