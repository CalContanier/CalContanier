<?php
/**
 * Author: 沧澜
 * Date: 2020-09-20
 */

namespace CalContainer\Components\Preg;


class PregAnnotation
{

    /**
     * 获取所有tag(@key(xxx), @key(method='xxx', prefix='xxx'), ...)
     * @param string $docComment
     * @param null $rules
     * @param null $default
     * @return array|null
     */
    public static function matchTags(string $docComment, $rules = null, $default = null)
    {
        $tagStr = is_array($rules) ? implode("|", $rules) : $rules;
        $tagStr = $tagStr ?: '\w+';
        if (preg_match_all("/\*?[ ]*@($tagStr)[ ]*(\(['\"]?(?:[^()]*?)['\"]?\)|\(?(?:\w+)\)?)?\n/s", $docComment, $match) && isset($match[2])) {
            dump($match);
            foreach ($match[2] as $index => $content) {
                $tags[$match[1][$index]] = self::toKeyValues($content);
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
    public static function toKeyValues(string $docComment)
    {
        if (preg_match_all("/(\w*)=['\"]*([^'\"(),]*)['\"]*/", $docComment, $match) && $match[1]) {
            return array_combine($match[1], $match[2]);
        } else {
            return trim($docComment, ' ()');
        }
    }

}