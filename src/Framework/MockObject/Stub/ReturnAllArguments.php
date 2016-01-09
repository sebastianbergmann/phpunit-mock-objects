<?php
class PHPUnit_Framework_MockObject_Stub_ReturnAllArguments extends PHPUnit_Framework_MockObject_Stub_Return
{
    /**
     * @param PHPUnit_Framework_MockObject_Invocation $invocation
     * @return mixed
     *
     * @author Luke Rodgers <lukerodgers90@gmail.com>
     */
    public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        if (isset($invocation->parameters)) {
            return $invocation->parameters;
        } else {
            return null;
        }
    }

    /**
     * @return string
     *
     * @author Luke Rodgers <lukerodgers90@gmail.com>
     */
    public function toString()
    {
        return sprintf('return all arguments');
    }
}