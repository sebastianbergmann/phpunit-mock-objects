<?php

use SebastianBergmann\Exporter\Exporter;

class PHPUnit_Framework_MockObject_Stub_Yield implements PHPUnit_Framework_MockObject_Stub
{
    protected $stack;
    protected $value;

    public function __construct($stack)
    {
        $this->stack = $stack;
    }

    public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        $this->value = array_shift($this->stack);

        if ($this->value instanceof PHPUnit_Framework_MockObject_Stub) {
            $this->value = $this->value->invoke($invocation);
        }

        yield $this->value;
    }

    public function toString()
    {
        $exporter = new Exporter;

        return sprintf(
            'return user-specified value %s',
            $exporter->export($this->value)
        );
    }
}
