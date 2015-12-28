<?php

class PHPUnit_Framework_MockObject_Stub_CallOriginal implements PHPUnit_Framework_MockObject_Stub
{


    public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        return $invocation->object->__phpunit_callOriginal($invocation->methodName, $invocation->parameters);
    }

    public function toString()
    {
        return 'calls the original method';
    }
}
