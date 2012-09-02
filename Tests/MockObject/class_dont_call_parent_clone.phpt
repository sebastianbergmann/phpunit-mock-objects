--TEST--
PHPUnit_Framework_MockObject_Generator::generate('Foo', array(), 'MockFoo', FALSE)
--FILE--
<?php
class Foo
{
    public function __clone()
    {
    }
}

require_once 'PHPUnit/Autoload.php';
require_once 'Text/Template.php';

$mock = PHPUnit_Framework_MockObject_Generator::generate(
  'Foo',
  array(),
  'MockFoo',
  FALSE
);

print $mock['code'];
?>
--EXPECTF--
class MockFoo extends Foo implements PHPUnit_Framework_MockObject_MockObject
{
    private static $phpunitMockObjectStaticInvocationMocker;
    private $phpunitMockObjectInvocationMocker;
    private $phpunitMockObjectId;
    private static $phpunitMockObjectNextId = 0;

    public function __clone()
    {
        $this->phpunitMockObjectInvocationMocker = clone $this->__phpunit_getInvocationMocker();
        $this->__phpunit_setId();
    }

    public function expects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public static function staticExpects(PHPUnit_Framework_MockObject_Matcher_Invocation $matcher)
    {
        return self::__phpunit_getStaticInvocationMocker()->expects($matcher);
    }

    public function __phpunit_getInvocationMocker()
    {
        if ($this->phpunitMockObjectInvocationMocker === NULL) {
            $this->phpunitMockObjectInvocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return $this->phpunitMockObjectInvocationMocker;
    }

    public static function __phpunit_getStaticInvocationMocker()
    {
        if (self::$phpunitMockObjectStaticInvocationMocker === NULL) {
            self::$phpunitMockObjectStaticInvocationMocker = new PHPUnit_Framework_MockObject_InvocationMocker;
        }

        return self::$phpunitMockObjectStaticInvocationMocker;
    }

    public function __phpunit_hasMatchers()
    {
        return self::__phpunit_getStaticInvocationMocker()->hasMatchers() ||
               $this->__phpunit_getInvocationMocker()->hasMatchers();
    }

    public function __phpunit_verify()
    {
        self::__phpunit_getStaticInvocationMocker()->verify();
        $this->__phpunit_getInvocationMocker()->verify();
    }

    public function __phpunit_cleanup()
    {
        self::$phpunitMockObjectStaticInvocationMocker = NULL;
        $this->phpunitMockObjectInvocationMocker       = NULL;
        $this->phpunitMockObjectId                     = NULL;
    }

    public function __phpunit_setId()
    {
        $this->phpunitMockObjectId = sprintf('%s#%s', get_class($this), self::$phpunitMockObjectNextId++);
    }
}

