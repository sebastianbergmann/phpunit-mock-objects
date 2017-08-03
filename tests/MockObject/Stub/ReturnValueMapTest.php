<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

class ReturnValueMapTest extends TestCase
{

  /**
  * @test
  **/
  public function returns_the_first_match_found()
  {
    $map = [
        ['a', 'b', 'c', 'd'],
        ['a', 'b', 'c', 'e'],
        ['e', 'f', 'g', 'h']
    ];

    $mock = $this->getMockBuilder(AnInterface::class)
                 ->getMock();

    $mock->expects($this->any())
         ->method('doSomething')
         ->will($this->returnValueMap($map));

    $this->assertEquals('d', $mock->doSomething('a', 'b', 'c'));
  }

  /**
  * @test
  **/
  public function accepts_framework_matchers() {
    $map = [
        [$this->lessThan(2), 1],
        [$this->greaterThanOrEqual(2), 2]
    ];

    $mock = $this->getMockBuilder(AnInterface::class)
                 ->getMock();

    $mock->expects($this->any())
         ->method('doSomething')
         ->will($this->returnValueMap($map));

    $this->assertEquals(1, $mock->doSomething(0));
    $this->assertEquals(2, $mock->doSomething(100));
  }

  /**
  * @test
  **/
  public function accepts_stub_for_return_value() {
    $callback = new PHPUnit_Framework_MockObject_Stub_ReturnCallback(
        function($arg) { return $arg + 1; }
    );

    $map = [
        [$this->lessThan(2), 1],
        [$this->greaterThanOrEqual(2), $callback]
    ];

    $mock = $this->getMockBuilder(AnInterface::class)
                 ->getMock();

    $mock->expects($this->any())
         ->method('doSomething')
         ->will($this->returnValueMap($map));


    $this->assertEquals(3, $mock->doSomething(2));
  }

  /**
  * @test
  **/
  public function returns_null_if_no_match_found()
  {
      $map = [
          ['a', 'b', 'c', 'd'],
      ];

      $mock = $this->getMockBuilder(AnInterface::class)
                   ->getMock();

      $mock->expects($this->any())
           ->method('doSomething')
           ->will($this->returnValueMap($map));

      $this->assertEquals(null, $mock->doSomething('foo', 'bar'));
  }
}
