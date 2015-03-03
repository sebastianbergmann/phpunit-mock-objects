<?php

namespace PHPUnit\Framework\MockObject\Tests\Fixture;

class MockableWithConstructorParamRequired
{
    private $datetime;

    public function __construct(\DateTime $datetime)
    {
        $this->datetime = $datetime;
        //some other stuff that I want to suppress during the unit tests.
    }

    public function doSomething()
    {
        $this->datetime->bar();
    }
}
