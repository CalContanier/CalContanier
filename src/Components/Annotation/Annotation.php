<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AnnotationParseInterface;

/**
 * Class Annotation
 * @package CalContainer\Components
 */
class Annotation implements AnnotationParseInterface
{
    /**
     * @var string
     */
    protected $docComment;
    
    /**
     * 标签tag
     * @var TagAnnotation
     */
    protected $tag;
    
    /**
     * 数据提供者
     * @var DataProviderAnnotation
     */
    protected $dataProvider;
    
    /**
     * 装饰器
     * @var array
     */
    protected $decorator;
    
    /**
     * Annotation constructor.
     * @param $docComment
     */
    public function __construct($docComment)
    {
        $this->docComment = $docComment;
        $this->parse($docComment);
    }
    
    /**
     * parse
     * @param string $docComment
     */
    public function parse($docComment)
    {
        $this->tag = new TagAnnotation($docComment);
    }
    
    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tag->getTags();
    }
    
    /**
     * @return array
     */
    public function getDataProviders(): array
    {
        return $this->dataProvider->getParams();
    }
    
    /**
     * @return array
     */
    public function getDecorator(): array
    {
        return $this->decorator;
    }
    
}