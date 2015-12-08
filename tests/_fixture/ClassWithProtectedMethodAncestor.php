<?php

class ClassWithProtectedMethodAncestor extends ClassWithProtectedMethod {
	public function mockableProtectedMethod() {
		return 'overridden-value';
	}
}