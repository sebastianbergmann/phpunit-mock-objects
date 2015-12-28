<?php

class CallOriginalTest extends PHPUnit_Framework_TestCase
{

  /**
  * @test
  **/
  public function calls_the_original_methods()
  {
      $mock = $this->getMock('Spy');
      $mock->method('getValue')
        ->willCallOriginal();

      $this->assertEquals(Spy::MAGIC_VALUE, $mock->getValue());
  }

  /**
  * @test
  * @expectedExceptionMessage Pass
  **/
  public function validates_expectations()
  {
    $mockBuilder = new PHPUnit_Framework_MockObject_Generator;
    $mock = $mockBuilder->getMock('Spy');
    $mock->expects($this->once())
      ->method('getValue')
      ->willCallOriginal();

    try {
      $mock->getValue();
      $mock->getValue();
      $this->fail("Failed to validate expectation on the spy object");
    } catch (PHPUnit_Framework_ExpectationFailedException $e) {
    }
  }

  /**
  * @test
  **/
  public function passes_arguments_to_the_original_function() {
    $mock = $this->getMock('Spy');
    $mock->method('calcValue')
      ->willCallOriginal();
    $this->assertEquals(16, $mock->calcValue(3,5,8));
  }

}

class Spy {
  const MAGIC_VALUE = 27;
  public function getValue() {
    return self::MAGIC_VALUE;
  }

  public function calcValue($x, $y, $z) {
    return $x + $y + $z;
  }
}
