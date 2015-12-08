<?php

/**
 * Calls original method value when it is mocked be getMockOverExistingObject()
 */
class PHPUnit_Framework_MockObject_Stub_CallOriginalMethod implements \PHPUnit_Framework_MockObject_Stub {
	/**
	 * @param \PHPUnit_Framework_MockObject_Invocation_Object $invocation
	 * @return mixed
	 */
	public function invoke(\PHPUnit_Framework_MockObject_Invocation $invocation) {
		$reflection = new \ReflectionClass($invocation->className);
		$method = $reflection->getMethod($invocation->methodName);
		$method->setAccessible(true);
		return $method->invokeArgs($invocation->object, $invocation->parameters);
	}

	/**
	 * Returns a string representation of the object.
	 *
	 * @return string
	 */
	public function toString() {
		return 'return original method value';
	}
}