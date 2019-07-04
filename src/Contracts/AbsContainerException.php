<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Contracts;

use Exception;
use Throwable;

/**
 * Class AbsException
 * @package CalContainer\Contracts
 */
abstract class AbsContainerException extends Exception
{
    
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @throws static
     */
    public static function throw($message = "", $code = 0, Throwable $previous = null)
    {
        throw new static($message, $code, $previous);
    }
    
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @return AbsContainerException
     */
    public static function create($message = "", $code = 0, Throwable $previous = null)
    {
        return new static($message, $code, $previous);
    }
    
}