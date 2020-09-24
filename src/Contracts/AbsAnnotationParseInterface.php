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
     * @var array|mixed
     */
    protected $annotations;

    /**
     * TagAnnotation constructor.
     * @param $docComment
     */
    public function __construct(string $docComment = '')
    {
        $this->init($docComment);
        $docComment && $this->parse($docComment);
    }

    /**
     * @param string $docComment
     */
    protected function init(string $docComment = '')
    {

    }

    /**
     * @param string $docComment
     * @return mixed|bool
     */
    public function parse(string $docComment)
    {
        $this->docComment = $docComment;
        if ($this->isParse = (bool)$docComment) {
            return ($this->annotations = $this->doParse($docComment)) ?: false;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getDocComment(): string
    {
        return $this->docComment;
    }

    /**
     * @return array|mixed
     */
    public function getAnnotations()
    {
        return $this->annotations ?: null;
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