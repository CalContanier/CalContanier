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
     * @param string $docComment
     * @return mixed
     */
    protected function doParse(string $docComment)
    {
        $this->docComment = $docComment;
        $this->tag = new TagAnnotation($docComment);
        $this->dataProvider = new DataProviderAnnotation();
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