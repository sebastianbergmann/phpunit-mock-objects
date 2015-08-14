<?php

class InvocationMockerTest extends PHPUnit_Framework_TestCase
{
    public function testExpectsWithNoValueMeansAtLeastOnce()
    {
        $mock = $this->getMock('stdClass', array('foo'));
        $mock->expects()->method('foo');
        $mock->foo('bar');
        $mock->foo('baz');
    }

    public function testExpectsWithNoValueFailsIfNoInvocation()
    {
        $mock = $this->getMock('stdClass', array('foo'));

        try {
            $mock->expects()->method('foo');
            $mock->__phpunit_verify();
        } catch (PHPUnit_Framework_ExpectationFailedException $ex) {
            $mock->foo();
        }
    }

    public function testExpectsWithIntegerValueMeansExactInvocationCount()
    {
        $mock = $this->getMock('stdClass', array('foo'));
        $mock->expects(2)->method('foo');
        $mock->foo('bar');
        $mock->foo('baz');
    }

    public function testExpectsWithIntegerFailsIfNoExactInvocationCount()
    {
        $mock = $this->getMock('stdClass', array('foo'));

        try {
            $mock->expects(2)->method('foo');
            $mock->foo('bar');
            $mock->__phpunit_verify();
        } catch (PHPUnit_Framework_ExpectationFailedException $ex) {
            $mock->foo();
        }
    }

    /**
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     */
    public function testExpectsThrowsOnUnexpectedMatcherType()
    {
        $mock = $this->getMock('stdClass', array('foo'));
        $mock->expects('unexpected')->method('foo');
    }

    public function testExpectsAllowsMatcherInterface()
    {
        $mock = $this->getMock('stdClass', array('foo'));
        $mock->expects($this->any())->method('foo');
    }
}
