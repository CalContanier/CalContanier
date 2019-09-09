<?php
/**
 * Author: 沧澜
 * Date: 2019-09-09
 */

namespace CalContainer\Exceptions;

use CalContainer\Contracts\AbsContainerException;
use Throwable;

/**
 * Class ContainerException
 * @package CalContainer\Exceptions
 * @method static static throw($message = "", $code = 0, Throwable $previous = null)
 * @method static static create($message = "", $code = 0, Throwable $previous = null)
 */
class ContainerException extends AbsContainerException
{
    
}