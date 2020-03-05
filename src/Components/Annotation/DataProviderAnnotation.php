<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AnnotationParseInterface;

/**
 * Class DataProviderAnnotation
 * @package CalContainer\Components\Annotation
 */
class DataProviderAnnotation implements AnnotationParseInterface
{
    /**
     * 参数列表
     * @var array
     */
    protected $params = [];
    
    /**
     * @param string $docComment
     * @return mixed
     */
    public function parse($docComment)
    {
        if ($docComment) {
            preg_match('/\*?[ ]*@(dataProvider|dataprovider)[ ]+([\\\w]+)\n/', $docComment, $match);
        
        }
    }
    
    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
    
}