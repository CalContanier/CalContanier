<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AbsAnnotationParseInterface;

/**
 * Class DataProviderAnnotation
 * @package CalContainer\Components\Annotation
 */
class DataProviderAnnotation extends AbsAnnotationParseInterface
{
    /**
     * 参数列表
     * @var array
     */
    protected $params = [];
    
    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $docComment
     * @return mixed
     */
    protected function doParse(string $docComment)
    {
        preg_match('/\*?[ ]*@(dataProvider|dataprovider)[ ]+([\\\w]+)\n/', $docComment, $match);
    }
}