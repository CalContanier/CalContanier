<?php
/**
 * Author: 沧澜
 * Date: 2020-09-16
 */

namespace CalContainer\Contracts;

/**
 * Class AbsAnnotationParseInterface
 * @package CalContainer\Contracts
 */
abstract class AbsAnnotationParseInterface implements AnnotationParseInterface
{

    /**
     * @var string
     */
    protected $docComment = '';

    /**
     * @var bool
     */
    protected $isParse = false;

    /**
     * TagAnnotation constructor.
     * @param $docComment
     */
    public function __construct(string $docComment = '')
    {
        $docComment && $this->parse($docComment);
    }

    /**
     * @param string $docComment
     * @return mixed|void
     */
    public function parse(string $docComment)
    {
        $this->docComment = $docComment;
        $this->isParse = $docComment && $this->doParse($docComment);
    }


    /**
     * @return string
     */
    public function getDocComment(): string
    {
        return $this->docComment;
    }

    /**
     * @return bool
     */
    public function isParse(): bool
    {
        return $this->isParse;
    }

    /**
     * @param string $docComment
     * @return mixed
     */
    abstract protected function doParse(string $docComment);

}