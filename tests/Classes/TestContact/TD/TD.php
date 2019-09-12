<?php
/**
 * Author: 沧澜
 * Date: 2019-09-11
 */

namespace CalContainer\Tests\Classes\TestContact\TD;


use CalContainer\Tests\Classes\Contracts\InterfaceContact;

class TD
{
    public function test(InterfaceContact $contact)
    {
        dump(static::class . " --- " . get_class($contact));
    }
    
    public function test2(InterfaceContact $contact)
    {
        dump(static::class . " --- " . get_class($contact));
    }
}