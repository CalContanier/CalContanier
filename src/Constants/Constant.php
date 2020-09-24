<?php
/**
 * Author: 沧澜
 * Date: 2020-09-17
 */

namespace CalContainer\Constants;

/**
 * Interface Constant
 * @package CalContainer\Constants
 */
interface Constant
{
    /* ======== SCOPE ======== */
    const SCOPE_ALL = 0b11;
    const SCOPE_CLASS = 0b1;
    const SCOPE_FUNCTION = 0b10;

    /* ======== ANNOTATION TYPE ======== */
    const ANO_TAG = 0b1;
    const ANO_DECORATOR = 0b10;
    const ANO_PROVIDER = 0b100;


}