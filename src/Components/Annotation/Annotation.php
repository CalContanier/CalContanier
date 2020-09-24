<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AbsAnnotationParseInterface;

/**
 * Class Annotation
 * @package CalContainer\Components
 */
class Annotation extends AbsAnnotationParseInterface
{

    /**
     * 标签tag
     * @var TagAnnotation
     */
    protected $tag;

    /**
     * @var AnnotationRegister
     */
    protected $register;
    
    /**
     * @param string $docComment
     */
    protected function init(string $docComment = '')
    {
        $this->tag = new TagAnnotation();
        $this->register = new AnnotationRegister();
    }


    /**
     * @param string $docComment
     * @return mixed
     */
    protected function doParse(string $docComment)
    {
        $this->tag->parse($docComment);
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tag->getTags();
    }

    /**
     * @return AnnotationRegister
     */
    public function register()
    {
        return $this->register;
    }
    
    
}