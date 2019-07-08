<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-05
 * Annotation:
 */

namespace CalContainer\Exceptions;

use CalContainer\Contracts\AbsContainerException;
use Throwable;

/**
 * Class InstanceCreateException
 * @package CalContainer\Exceptions
 * @method static static throw($message = "", $code = 0, Throwable $previous = null)
 * @method static static create($message = "", $code = 0, Throwable $previous = null)
 */
class InstanceCreateException extends AbsContainerException
{
    
}