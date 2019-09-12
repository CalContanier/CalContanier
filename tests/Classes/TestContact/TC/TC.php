<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Tests\Classes\TestContact\TC;


use CalContainer\Tests\Classes\Contracts\InterfaceContact;

class TC
{
    public function test(InterfaceContact $contact)
    {
        dump(static::class . " --- " . get_class($contact));
    }
}