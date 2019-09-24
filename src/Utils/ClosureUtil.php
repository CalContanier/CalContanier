<?php
/**
 * Author: 沧澜
 * Date: 2019-09-24
 */

namespace CalContainer\Utils;


use Closure;

class ClosureUtil
{
    /**
     * @param Closure|mixed $closure
     * @param array $params
     * @param mixed|null $default
     * @return mixed
     */
    final public static function checkRun($closure, array $params = [], $default = null)
    {
        if ($closure instanceof Closure) {
            return call_user_func_array($closure, $params);
        } else if (is_array($closure) && count($closure) >= 2) {
            return call_user_func_array(array_slice($closure, 0, 2), $params);
        } else if (is_string($closure) && count(explode("::", $closure)) == 2) {
            return self::checkRun(explode("::", $closure), $params);
        } else {
            return $default ?? $closure;
        }
    }
}