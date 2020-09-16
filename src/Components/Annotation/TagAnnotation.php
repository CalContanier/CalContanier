<?php
/**
 * Author: 沧澜
 * Date: 2020-03-02
 */

namespace CalContainer\Components\Annotation;

use CalContainer\Contracts\AbsAnnotationParseInterface;

/**
 * Class TagAnnotation
 * @package CalContainer\Components\Annotation
 */
class TagAnnotation extends AbsAnnotationParseInterface
{
    
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @param string $docComment
     * @return mixed
     */
    protected function doParse(string $docComment)
    {
        if ($docComment && $classTags = $this->matchTags($docComment)) {
            foreach ($classTags as $tag => $content) {
                $this->tags[$tag] = $content;
            }
        }
        return $this->tags;
    }

    /**
     * 获取所有tag(@key(xxx), @key(method='xxx', prefix='xxx'), ...)
     * @param string $docComment
     * @param null $default
     * @return array|null
     */
    protected function matchTags(string $docComment, $default = null)
    {
        if (preg_match_all("/\*?[ ]*@(\w+)\(['\"]?([^()]*?)['\"]?\)\n/s", $docComment, $match) && isset($match[2])) {
            foreach ($match[2] as $index => $content) {
                $tags[$match[1][$index]] = $this->toKeyValues($content);
            }
            return $tags ?? $default;
        } else {
            return $default;
        }
    }
    
    /**
     * 将字符串(key='values')转换为键值对
     * @param string $docComment
     * @return array|mixed
     * @example a='a', b='b' ==> ['a' => 'a', 'b' => 'b']
     */
    protected function toKeyValues(string $docComment)
    {
        if (preg_match_all("/(\w*)=['\"]*([^'\"()]*)['\"]*/", $docComment, $match) && $match[1]) {
            return array_combine($match[1], $match[2]);
        } else {
            return $docComment;
        }
    }
    
    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}