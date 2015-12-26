<?php

class YieldTest extends PHPUnit_Framework_TestCase
{

  /**
  * @test
  **/
  public function yields_multiple_values()
  {
      $mock = $this->getMock('AnInterface');
      $mock->expects($this->any())
           ->method('doSomething')
           ->willYield(1, 3, 5);

      $expectedValues = [1,3,5];
      $current = 0;
      foreach($mock->doSomething() as $value) {
        $this->assertEquals($expectedValues[$current++], $value);
      }
  }

}
