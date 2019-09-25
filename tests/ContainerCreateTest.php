<?php
/**
 * Author: 沧澜
 * Date: 2019-09-09
 */

namespace CalContainer\Tests;

use CalContainer\Container;
use CalContainer\Exceptions\ContainerException;
use CalContainer\Tests\Classes\A;
use CalContainer\Tests\Classes\B;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ContainerCreateTest extends TestCase
{
    /**
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function testCreate()
    {
        $container = Container::getInstance();
        $classA = $container->create(A::class);
        $classB = $container->get(B::class);
        /* ======== check create ======== */
        $this->assertInstanceOf(A::class, $classA);
        $this->assertInstanceOf(B::class, $classB);
        /* ======== check ClassA ======== */
        $this->assertFalse($container->has(A::class));
        Container::bindRegister()->bind(A::class, $classA);
        $this->assertTrue($container->has(A::class));
        
        /* ======== check ClassB ======== */
        $this->assertFalse($container->has(B::class));
        Container::bindRegister()->bind($classB);
        $this->assertTrue($container->has(B::class));
        
    }
    
    
    public function testCreateException()
    {
    
    }



}