--TEST--
PHPUnit_Framework_MockObject_Generator::generate('Foo', null, 'ProxyFoo', true, true, true, true)
--FILE--
<?php
class Foo
{
    public function bar(Foo $foo)
    {
    }

    public function baz(Foo $foo)
    {
    }
}

require __DIR__ . '/../../vendor/autoload.php';

$generator = new PHPUnit_Framework_MockObject_Generator;

$mock = $generator->generate(
    'Foo', array(), 'ProxyFoo', true, true, true, true
);

print $mock['code'];
?>
--EXPECTF--
class ProxyFoo extends Foo implements PHPUnit_Framework_MockObject_MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;

    public function __clone()
    {
        $this->__phpunit_invocationMocker = clone $this->__phpunit_getInvocationMocker();
    }

    public function bar(Foo $foo)
    {
        $arguments = array($foo);
        $count     = func_num_args();

        if ($count > 1) {
            $_arguments = func_get_args();

            for ($i = 1; $i < $count; $i++) {
                $arguments[] = $_arguments[$i];
            }
        }

        $this->__phpunit_getInvocationMocker()->invoke(
            new PHPUnit_Framework_MockObject_Invocation_Object(
                'Foo', 'bar', $arguments, '', $this, true
            )
        );

        return call_user_func_array(array($this->__phpunit_originalObject, "bar"), $arguments);
    }

    public function baz(Foo $foo)
    {
        $arguments = array($foo);
        $count     = func_num_args();

        if ($count > 1) {
            $_arguments = func_get_args();

            for ($i = 1; $i < $count; $i++) {
                $arguments[] = $_arguments[$i];
            }
        }

        $this->__phpunit_getInvocationMocker()->invoke(
            new PHPUnit_Framework_MockObject_Invocation_Object(
                'Foo', 'baz', $arguments, '', $this, true
            )
        );

        return call_user_func_array(array($this->__phpunit_originalObject, "baz"), $arguments);
    }

    public function expects($matcher = null)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public function method()
    {
        $any = new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount;
        $expects = $this->expects($any);
        return call_user_func_array(array($expects, 'method'), func_get_args());
    }

    public function __phpunit_setOriginalObject($originalObject)
    {
        $this->__phpunit_originalObject = $originalObject;
    }

    public function __phpunit_getInvocationMocker()
    {
        if ($this->__phpunit_invocationMocker === null) {
            $this->__phpunit_invocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return $this->__phpunit_invocationMocker;
    }

    public function __phpunit_hasMatchers()
    {
        return $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __phpunit_verify($unsetInvocationMocker = true)
    {
        $this->__phpunit_getInvocationMocker()->verify();

        if ($unsetInvocationMocker) {
            $this->__phpunit_invocationMocker = null;
        }
    }
}
