<?php
/**
 * Author: 沧澜
 * Date: 2020-09-24
 */

namespace CalContainer\Contracts;


use CalContainer\Constants\Constant;

/**
 * Class AbsTagAnnotation
 * @package CalContainer\Contracts
 */
abstract class AbsProviderAnnotation implements AnnotationInterface
{
    /**
     * 注册类型参数
     * @return string
     */
    public function type()
    {
        return Constant::ANO_PROVIDER;
    }

}