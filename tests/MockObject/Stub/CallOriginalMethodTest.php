<?php

class Framework_MockObject_Stub_CallOriginalMethodTest extends PHPUnit_Framework_TestCase
{
	public function testOriginalMethodCalled()
	{
		$invocation = new PHPUnit_Framework_MockObject_Invocation_Object(
			'ClassWithProtectedMethod',
			'mockableProtectedMethod',
			[],
			'ReturnType',
			$srcObj = new ClassWithProtectedMethodAncestor()
		);
		$this->assertEquals('overridden-value', $srcObj->mockableProtectedMethod());
		$call = new PHPUnit_Framework_MockObject_Stub_CallOriginalMethod();
		$this->assertEquals('real-value', $call->invoke($invocation));
	}
}
