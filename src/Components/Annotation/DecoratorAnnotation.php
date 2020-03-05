<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;


use CalContainer\Contracts\AnnotationParseInterface;

class DecoratorAnnotation implements AnnotationParseInterface
{
    
    /**
     * 装饰器
     * @var array
     */
    protected $decorators = [];
    
    
    /**
     * @param string $docComment
     * @return mixed
     */
    public function parse($docComment)
    {
    
    }
}