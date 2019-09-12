<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Tests\Classes\TestContact\TB;


use CalContainer\Tests\Classes\Contracts\InterfaceContact;

class TB
{
    public function test(InterfaceContact $contact)
    {
        dump(static::class . " --- " . get_class($contact));
    }
}