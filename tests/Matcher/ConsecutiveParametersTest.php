<?php
/*
 * This file is part of the phpunit-mock-objects package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;

class ConsecutiveParametersTest extends TestCase
{
    public function testIntegration()
    {
        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['foo'])
                     ->getMock();

        $mock->expects($this->any())
             ->method('foo')
             ->withConsecutive(
                 ['bar'],
                 [21, 42]
             );

        $this->assertNull($mock->foo('bar'));
        $this->assertNull($mock->foo(21, 42));
    }

    public function testIntegrationWithLessAssertionsThanMethodCalls()
    {
        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['foo'])
                     ->getMock();

        $mock->expects($this->any())
             ->method('foo')
             ->withConsecutive(
                 ['bar']
             );

        $this->assertNull($mock->foo('bar'));
        $this->assertNull($mock->foo(21, 42));
    }

    public function testIntegrationExpectingException()
    {
        $mock = $this->getMockBuilder(stdClass::class)
                     ->setMethods(['foo'])
                     ->getMock();

        $mock->expects($this->any())
             ->method('foo')
             ->withConsecutive(
                 ['bar'],
                 [21, 42]
             );

        $mock->foo('bar');

        $this->expectException(ExpectationFailedException::class);

        $mock->foo('invalid');
    }

    public function testCallbackConstraintOnlyEvaluatedOnce()
    {
        $mock  = $this->getMockBuilder(Foo::class)->setMethods(['bar'])->getMock();
        $callCount = ['call_1' => 0, 'call_2' => 0];

        $mock->expects($this->exactly(2))->method('bar')
            ->withConsecutive(
                [
                    $this->callback(function ($argument) use (&$callCount) {
                        $this->assertEquals('call_1', $argument);

                        $callCount['call_1']++;
                        $this->assertEquals(1, $callCount['call_1']);

                        return true;
                    })
                ],
                [
                    $this->callback(function ($argument) use (&$callCount) {
                        $this->assertEquals('call_2', $argument);

                        $callCount['call_2']++;
                        $this->assertEquals(1, $callCount['call_2']);

                        return true;
                    })
                ]);

        $mock->bar('call_1');
        $mock->bar('call_2');

        $this->assertEquals(['call_1' => 1, 'call_2' => 1], $callCount);
    }
}
