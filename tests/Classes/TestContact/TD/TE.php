<?php
/**
 * Author: 沧澜
 * Date: 2019-09-12
 */

namespace CalContainer\Tests\Classes\TestContact\TD;


use CalContainer\Tests\Classes\Contracts\InterfaceContact;

class TE
{
    public function test(InterfaceContact $contact, InterfaceContact $contact2)
    {
        dump(static::class . " --- " . get_class($contact));
    }
}