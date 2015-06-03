--TEST--
PHPUnit_Framework_MockObject_Generator::generate('NS\Foo', array(), 'MockFoo', TRUE)
--FILE--
<?php
namespace NS;

class Foo
{
    public function __clone()
    {
    }
}

require __DIR__ . '/../../vendor/autoload.php';

$generator = new \PHPUnit_Framework_MockObject_Generator;

$mock = $generator->generate(
  'NS\Foo',
  array(),
  'MockFoo',
  TRUE
);

print $mock['code'];
?>
--EXPECTF--
class MockFoo extends NS\Foo implements PHPUnit_Framework_MockObject_MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;

    public function __clone()
    {
        $this->__phpunit_invocationMocker = clone $this->__phpunit_getInvocationMocker();
        parent::__clone();
    }

    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public function expectsOnce()
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedCount(1));
    }

    public function expectsAny()
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount);
    }

    public function expectsExactly($count)
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedCount($count));
    }

    public function expectsNever()
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedCount(0));
    }

    public function expectsAt($index)
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtIndex($index));
    }

    public function expectsAtLeastOnce()
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce);
    }

    public function expectsAtMost($count)
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtMostCount($count));
    }

    public function expectsAtLeast($count)
    {
        return $this->expects(new PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastCount($count));
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
        if ($this->__phpunit_invocationMocker === NULL) {
            $this->__phpunit_invocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return $this->__phpunit_invocationMocker;
    }

    public function __phpunit_hasMatchers()
    {
        return $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __phpunit_verify()
    {
        $this->__phpunit_getInvocationMocker()->verify();
        $this->__phpunit_invocationMocker = NULL;
    }
}
