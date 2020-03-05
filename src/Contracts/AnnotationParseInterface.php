<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Contracts;


interface AnnotationParseInterface
{
    /**
     * @param string $docComment
     * @return mixed
     */
    public function parse($docComment);
    
}