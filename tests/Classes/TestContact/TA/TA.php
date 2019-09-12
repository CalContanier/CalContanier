<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Tests\Classes\TestContact\TA;



use CalContainer\Tests\Classes\Contracts\InterfaceContact;

class TA
{
    public function test(InterfaceContact $contact)
    {
        dump(static::class . " --- " . get_class($contact));
    }
}