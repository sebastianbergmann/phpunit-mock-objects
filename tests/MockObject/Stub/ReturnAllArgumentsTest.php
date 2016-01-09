<?php

class ReturnAllArgumentsTest extends PHPUnit_Framework_TestCase
{
    public function testStubbedReturnArguments()
    {
        $mock = $this->getMock('AnInterface');
        $mock->expects($this->any())
            ->method('doSomething')
            ->willReturnAllArguments();
        $this->assertEquals(array('a', 'b', 'c'), $mock->doSomething('a', 'b', 'c'));

        $mock = $this->getMock('AnInterface');
        $mock->expects($this->any())
            ->method('doSomething')
            ->willReturnAllArguments();
        $this->assertEquals(array('1', '5', '9'), $mock->doSomething('1', '5', '9'));
    }
}