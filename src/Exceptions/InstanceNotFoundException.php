<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Exceptions;

use CalContainer\Contracts\AbsContainerException;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class InstanceNotFoundException
 * @package CalContainer\Exceptions
 *
 */
class InstanceNotFoundException extends AbsContainerException implements NotFoundExceptionInterface
{

}