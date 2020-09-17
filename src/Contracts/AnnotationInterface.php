<?php
/**
 * Author: 沧澜
 * Date: 2020-09-17
 */

namespace CalContainer\Contracts;

/**
 * Interface AnnotationInterface
 * @package CalContainer\Contracts
 */
interface AnnotationInterface
{
    /**
     * annotation name
     * @example @{name}
     * @return string
     */
    public function name(): string;

    /**
     * run
     * @param array|string $params
     * @return void
     */
    public function run($params);

    /**
     * tag过滤参数(数组或者为空)
     * @example null 、['name', 'psr', ...] 、'name'
     * @return array|mixed|null|void
     */
    public function tagParams();

    /**
     * 作用范围, 可选: class, function
     * @example Constant::SCOPE_ALL | Constant::SCOPE_CLASS | Constant::SCOPE_FUNCTION
     * @return array|string|mixed|void
     */
    public function scope();

}