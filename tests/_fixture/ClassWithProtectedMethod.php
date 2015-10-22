<?php

class ClassWithProtectedMethod {
	// Should be overridden and mocked
	protected function mockableProtectedMethod() {
		return 'real-value';
	}

	public function testMethod() {
		// Should call overridden mocked method
		return $this->mockableProtectedMethod();
	}
}