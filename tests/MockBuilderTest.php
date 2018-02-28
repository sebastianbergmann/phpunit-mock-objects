<?php
/*
 * This file is part of the PHPUnit_MockObject package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @since      File available since Release 1.0.0
 */
class Framework_MockBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testMockBuilderRequiresClassName()
    {
        $spec = $this->getMockBuilder('Mockable');
        $mock = $spec->getMock();
        $this->assertTrue($mock instanceof Mockable);
    }

    public function testByDefaultMocksAllMethods()
    {
        $spec = $this->getMockBuilder('Mockable');
        $mock = $spec->getMock();
        $this->assertNull($mock->mockableMethod());
        $this->assertNull($mock->anotherMockableMethod());
    }

    public function testMethodsToMockCanBeSpecified()
    {
        $spec = $this->getMockBuilder('Mockable');
        $spec->setMethods(array('mockableMethod'));
        $mock = $spec->getMock();
        $this->assertNull($mock->mockableMethod());
        $this->assertTrue($mock->anotherMockableMethod());
    }

    public function testByDefaultDoesNotPassArgumentsToTheConstructor()
    {
        $spec = $this->getMockBuilder('Mockable');
        $mock = $spec->getMock();
        $this->assertEquals(array(null, null), $mock->constructorArgs);
    }

    public function testMockClassNameCanBeSpecified()
    {
        $spec = $this->getMockBuilder('Mockable');
        $spec->setMockClassName('ACustomClassName');
        $mock = $spec->getMock();
        $this->assertTrue($mock instanceof ACustomClassName);
    }

    public function testConstructorArgumentsCanBeSpecified()
    {
        $spec = $this->getMockBuilder('Mockable');
        $spec->setConstructorArgs($expected = array(23, 42));
        $mock = $spec->getMock();
        $this->assertEquals($expected, $mock->constructorArgs);
    }

    public function testOriginalConstructorCanBeDisabled()
    {
        $spec = $this->getMockBuilder('Mockable');
        $spec->disableOriginalConstructor();
        $mock = $spec->getMock();
        $this->assertNull($mock->constructorArgs);
    }

    public function testByDefaultOriginalCloneIsPreserved()
    {
        $spec = $this->getMockBuilder('Mockable');
        $mock = $spec->getMock();
        $cloned = clone $mock;
        $this->assertTrue($cloned->cloned);
    }

    public function testOriginalCloneCanBeDisabled()
    {
        $spec = $this->getMockBuilder('Mockable');
        $spec->disableOriginalClone();
        $mock = $spec->getMock();
        $mock->cloned = false;
        $cloned = clone $mock;
        $this->assertFalse($cloned->cloned);
    }

    /**
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     * @expectedExceptionMessage Class "Prophecy\Prophecy\RevealerInterface" does not exist.
     */
    public function testCallingAutoloadCanBeDisabled()
    {
        $this->getMockBuilder('Prophecy\Prophecy\RevealerInterface')
            ->disableAutoload()
            ->getMockForAbstractClass();
    }

    /**
     * @depends testCallingAutoloadCanBeDisabled
     */
    public function testPostDisabledAutoload()
    {
        $this->assertTrue(interface_exists('Prophecy\Prophecy\RevealerInterface', true));
    }

    public function testProvidesAFluentInterface()
    {
        $spec = $this->getMockBuilder('Mockable')
                     ->setMethods(array('mockableMethod'))
                     ->setConstructorArgs(array())
                     ->setMockClassName('DummyClassName')
                     ->disableOriginalConstructor()
                     ->disableOriginalClone()
                     ->disableAutoload();
        $this->assertTrue($spec instanceof PHPUnit_Framework_MockObject_MockBuilder);
    }

    /**
     * @requires PHP 5.4.0
     */
    public function testGetMockForTrait()
    {
        $spec = $this->getMockBuilder('AbstractTrait')
            ->setMethods(array('mockableMethod'))
            ->enableOriginalConstructor()
            ->enableAutoload()
            ->enableOriginalClone()
            ->disableArgumentCloning()
            ->setProxyTarget('parent')
            ->disableProxyingToOriginalMethods();

        $mock = $spec->getMockForTrait();
        $mock->expects($this->once())->method('mockableMethod')->willReturn(false);
        $mock->expects($this->once())->method('doSomething')->with(false);

        $this->assertTrue(method_exists($mock, 'doSomething'));
        $this->assertFalse($mock->mockableMethod());
        $this->assertTrue($mock->anotherMockableMethod());
        $this->assertNull($mock->doSomething(false));
    }
}
