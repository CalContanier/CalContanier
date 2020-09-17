<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Contracts;

/**
 * Interface AnnotationParseInterface
 * @package CalContainer\Contracts
 */
interface AnnotationParseInterface
{
    /**
     * @param string $docComment
     * @return mixed|bool
     */
    public function parse(string $docComment);
    
}