<?php
class Framework_MockObject_GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_Generator
     */
    protected $generator;

    protected function setUp()
    {
        $this->generator = new PHPUnit_Framework_MockObject_Generator;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMockObjectGenerator()
    {
        return $this->generator;
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #1 (No Value) of PHPUnit_Framework_MockObject_Generator::getMock() must be a array or string
     */
    public function testGetMockForInvalidType()
    {
        $this->generator->getMock(false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException InvalidArgumentException
     */
    public function testGetMockForInvalidMethods()
    {
        $this->generator->getMock('', '');
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #4 (No Value) of PHPUnit_Framework_MockObject_Generator::getMock() must be a string
     */
    public function testGetMockForInvalidMockClassName()
    {
        $this->generator->getMock('', array(), array(), false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     * @expectedExceptionMessage Class "Framework_MockObject_GeneratorTest" already exists.
     */
    public function testGetMockForAlreadyExistsMockClassName()
    {
        $this->generator->getMock('', null, array(), get_class($this));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_Exception
     */
    public function testGetMockFailsWhenInvalidFunctionNameIsPassedInAsAFunctionToMock()
    {
        $this->generator->getMock('StdClass', array(0));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     */
    public function testGetMockCanCreateNonExistingFunctions()
    {
        $mock = $this->generator->getMock('StdClass', array('testFunction'));
        $this->assertTrue(method_exists($mock, 'testFunction'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     * @expectedExceptionMessage duplicates: "foo, foo"
     */
    public function testGetMockGeneratorFails()
    {
        $mock = $this->generator->getMock('StdClass', array('foo', 'foo'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassDoesNotFailWhenFakingInterfaces()
    {
        $mock = $this->generator->getMockForAbstractClass('Countable');
        $this->assertTrue(method_exists($mock, 'count'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassStubbingAbstractClass()
    {
        $mock = $this->generator->getMockForAbstractClass('AbstractMockTestClass');
        $this->assertTrue(method_exists($mock, 'doSomething'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassWithNonExistentMethods()
    {
        $mock = $this->generator->getMockForAbstractClass(
            'AbstractMockTestClass',
            array(),
            '',
            true,
            true,
            true,
            array('nonexistentMethod')
        );

        $this->assertTrue(method_exists($mock, 'nonexistentMethod'));
        $this->assertTrue(method_exists($mock, 'doSomething'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassShouldCreateStubsOnlyForAbstractMethodWhenNoMethodsWereInformed()
    {
        $mock = $this->getMockForAbstractClass('AbstractMockTestClass');

        $mock->expects($this->any())
             ->method('doSomething')
             ->willReturn('testing');

        $this->assertEquals('testing', $mock->doSomething());
        $this->assertEquals(1, $mock->returnAnything());
    }

    /**
     * @dataProvider getMockForAbstractClassExpectsInvalidArgumentExceptionDataprovider
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     * @expectedException PHPUnit_Framework_Exception
     */
    public function testGetMockForAbstractClassExpectingInvalidArgumentException($className, $mockClassName)
    {
        $mock = $this->generator->getMockForAbstractClass($className, array(), $mockClassName);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     */
    public function testGetMockForAbstractClassAbstractClassDoesNotExist()
    {
        $mock = $this->generator->getMockForAbstractClass('Tux');
    }

    /**
     * Dataprovider for test "testGetMockForAbstractClassExpectingInvalidArgumentException"
     */
    public static function getMockForAbstractClassExpectsInvalidArgumentExceptionDataprovider()
    {
        return array(
            'className not a string' => array(array(), ''),
            'mockClassName not a string' => array('Countable', new StdClass),
        );
    }

    public function testGetMockForAbstractClassWithNonAbstractClass()
    {
        $mock = $this->generator->getMockForAbstractClass($class = 'Bar');

        $this->assertInstanceOf($class, $mock);
        $this->assertNotEquals($class, $mockClass = get_class($mock));

        $class   = new ReflectionClass($mockClass);
        $methods = array();
        foreach ($class->getMethods() as $method) {
            if ($method->class == $mockClass && substr($method->name, 0, 10) != '__phpunit_')
                $methods[] = $method->name;
        }
        $this->assertEquals(array('__clone', 'expects', 'method'), $methods);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForTrait
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #1 (No Value) of PHPUnit_Framework_MockObject_Generator::getMockForTrait() must be a string
     */
    public function testGetMockForTraitWithInvalidTraitName()
    {
        $this->generator->getMockForTrait(false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForTrait
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #3 (No Value) of PHPUnit_Framework_MockObject_Generator::getMockForTrait() must be a string
     */
    public function testGetMockForTraitWithInvalidMockClassName()
    {
        $this->generator->getMockForTrait('', array(), false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForTrait
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     * @expectedExceptionMessage Trait "Framework_MockObject_GeneratorTest" does not exist.
     */
    public function testGetMockForTraitWithNonExistsTraitName()
    {
        $this->generator->getMockForTrait(get_class($this));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForTrait
     * @requires PHP 5.4.0
     */
    public function testGetMockForTraitWithNonExistentMethodsAndNonAbstractMethods()
    {
        $mock = $this->generator->getMockForTrait(
            'AbstractTrait',
            array(),
            '',
            true,
            true,
            true,
            array('nonexistentMethod')
        );

        $this->assertTrue(method_exists($mock, 'nonexistentMethod'));
        $this->assertTrue(method_exists($mock, 'doSomething'));
        $this->assertTrue($mock->mockableMethod());
        $this->assertTrue($mock->anotherMockableMethod());
    }

    /**
     * @covers   PHPUnit_Framework_MockObject_Generator::getMockForTrait
     * @requires PHP 5.4.0
     */
    public function testGetMockForTraitStubbingAbstractMethod()
    {
        $mock = $this->generator->getMockForTrait('AbstractTrait');
        $this->assertTrue(method_exists($mock, 'doSomething'));
    }

    /**
     * @requires PHP 5.4.0
     */
    public function testGetMockForSingletonWithReflectionSuccess()
    {
        // Probably, this should be moved to tests/autoload.php
        require_once __DIR__ . '/_fixture/SingletonClass.php';

        $mock = $this->generator->getMock('SingletonClass', array('doSomething'), array(), '', false);
        $this->assertInstanceOf('SingletonClass', $mock);
    }

    /**
     * Same as "testGetMockForSingletonWithReflectionSuccess", but we expect
     * warning for PHP < 5.4.0 since PHPUnit will try to execute private __wakeup
     * on unserialize
     */
    public function testGetMockForSingletonWithUnserializeFail()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $this->markTestSkipped('Only for PHP < 5.4.0');
        }

        $this->setExpectedException('PHPUnit_Framework_MockObject_RuntimeException');

        // Probably, this should be moved to tests/autoload.php
        require_once __DIR__ . '/_fixture/SingletonClass.php';

        $mock = $this->generator->getMock('SingletonClass', array('doSomething'), array(), '', false);
    }

    /**
     * ReflectionClass::getMethods for SoapClient on PHP 5.3 produces PHP Fatal Error
     * @runInSeparateProcess
     */
    public function testGetMockForSoapClientReflectionMethodsDuplication()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $this->markTestSkipped('Only for PHP < 5.4.0');
        }

        $mock = $this->generator->getMock('SoapClient', array(), array(), '', false);
        $this->assertInstanceOf('SoapClient', $mock);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::generateClassFromWsdl
     */
    public function testGenerateClassFromWsdlWithoutNeedleExtension()
    {
        if (extension_loaded('soap')) {
            $this->markTestSkipped('Only for PHP without SOAP extension');
        } else {
            $this->setExpectedException('PHPUnit_Framework_MockObject_RuntimeException', 'The SOAP extension is required');
            $this->generator->generateClassFromWsdl('', null);
        }
    }

    /**
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Interface "Framework_MockObject_GeneratorTest" does not exist.
     */
    public function testGetMockGeneratorForNonExistsInterface()
    {
        $this->generator->getMock(array('Traversable', get_class($this)), null);
    }

    /**
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Duplicate method "offsetGet" not allowed.
     */
    public function testGetMockGeneratorForDuplicatedMethods()
    {
        $this->generator->getMock(array(get_class($this), 'ArrayAccess'), array('offsetGet'));
    }

    /**
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Class "Doctrine\Instantiator\Instantiator" is declared "final" and cannot be mocked.
     */
    public function testGetMockGeneratorForFinalClass()
    {
        $this->generator->getMock('Doctrine\Instantiator\Instantiator', null);
    }

    public function testGetMockForNonExistsClassWithNamespace()
    {
        $mock = $this->getMock($class = 'Bar\Foo\NotFound', array($method = 'barFoo'));
        $mock->expects($this->once())->method($method)->willReturn($method);

        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(method_exists($mock, $method));
        $this->assertNotEquals($class, get_class($mock));
        $this->assertInstanceOf($class, $mock);
        $this->assertEquals($method, $mock->$method());
    }

    public function testGetMockWithOriginalMethods()
    {
        $mock = $this->generator->getMock('Mockable', null, array(0), '', true, true, true, false, true);

        $this->assertAttributeEquals($args = array(0, null), 'constructorArgs', $mock);
        $this->assertAttributeInstanceOf('Mockable', '__phpunit_originalObject', $mock);
        $this->assertAttributeEquals($args, 'constructorArgs', $this->getObjectAttribute($mock, '__phpunit_originalObject'));

        $mock->__phpunit_setOriginalObject(null);
        foreach (get_class_methods('Mockable') as $method) {
            if ($method[0] != '_')
                $this->assertTrue($mock->$method());
        }
    }

    public function testGetMockWithParentAsProxy()
    {
        $mock = $this->getMock('Bar', array('doSomething'), array(), '', false, true, true, false, true, 'parent');
        $mock->expects($this->once())->method('doSomething')->with();

        $this->assertAttributeEquals('parent', '__phpunit_originalObject', $mock);
        $this->assertEquals('result', $mock->doSomethingElse(true));
    }

    public function testGetMockForInterfaceWithExtraMethod()
    {
        $mock = $this->getMock($class = 'AnotherInterface', array($method = 'foo'));
        $mock->expects($this->once())->method($method)->willReturn($method);

        $this->assertInstanceOf($class, $mock);
        $this->assertTrue(method_exists($mock, $method));
        $this->assertEquals($method, $mock->$method());
    }

    public function testGetMockForMultipleInterfaces()
    {
        $types = array('ClassWithStaticMethod', 'AnInterface', 'AnotherInterface');
        $mock = $this->getMock($types, array($method = 'foo', 'staticMethod'));
        $mock->expects($this->once())->method($method)->willReturn($method);

        foreach ($types as $class)
            $this->assertInstanceOf($class, $mock);

        $this->assertTrue(method_exists($mock, $method));
        $this->assertEquals($method, $mock->$method());

        $this->setExpectedException('PHPUnit_Framework_MockObject_BadMethodCallException');
        $mock->staticMethod();
    }

    public function testGetMockForReferenceMethod()
    {
        $mock = $this->getMock('Foo', array($method = 'doSomethingByRef'));
        $mock->expects($this->once())->method($method)->with($this->logicalOr('baz', 'baz1'))->willReturnCallback(function (&$param) {
            $param .= '1';
            return $param;
        });

        $param = 'baz';
        $result =& $mock->$method($param);

        $this->assertEquals('baz1', $param);
        $this->assertEquals($param, $result);

        $result = 'baz2';
        $this->assertNotEquals('baz2', $param);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getObjectForTrait
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #1 (No Value) of PHPUnit_Framework_MockObject_Generator::getObjectForTrait() must be a string
     */
    public function testGetObjectForTraitWithInvalidTraitName()
    {
        $this->generator->getObjectForTrait(false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getObjectForTrait
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Argument #3 (No Value) of PHPUnit_Framework_MockObject_Generator::getObjectForTrait() must be a string
     */
    public function testGetObjectForTraitWithInvalidMockClassName()
    {
        $this->generator->getObjectForTrait('', array(), false);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getObjectForTrait
     * @expectedException PHPUnit_Framework_MockObject_RuntimeException
     * @expectedExceptionMessage Trait "Framework_MockObject_GeneratorTest" does not exist.
     */
    public function testGetObjectForTraitWithNonExistsTraitName()
    {
        $this->generator->getObjectForTrait(get_class($this));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getObjectForTrait
     * @requires PHP 5.4.0
     * @runInSeparateProcess
     */
    public function testGetObjectForTrait()
    {
        if (!class_exists($trait = 'FooTrait', false)) {
            eval("trait $trait { public function foo() { return true; } }");
        }

        $object = $this->generator->getObjectForTrait($trait, array(), $class = 'NonMockTrait');
        $this->assertInstanceOf($class, $object);

        $this->assertTrue(method_exists($object, $method = 'foo'));
        $this->assertTrue($object->$method());
    }

    public function testGetMockWithNamespacedMockClassNameForInterface()
    {
        $mock = $this->getMock('ArrayAccess', array($method = 'foo'), array(), $class = 'Bar\Foo\Container');
        $mock->expects($this->once())->method($method)->with($method)->willReturnSelf();
        $mock->expects($this->exactly(2))->method('offsetGet')->with($method)->willReturnArgument(0);
        $mock->expects($this->once())->method('offsetExists')->with($method)->willReturnCallback(array($mock, 'offsetGet'));

        $this->assertInstanceOf($class, $mock);
        $this->assertTrue(class_exists($class, false));

        $this->assertSame($mock, $mock->$method($method));
        $this->assertEquals($method, $mock[$method]);
        $this->assertNotEmpty(isset($mock[$method]));

        // verify `toString()` methods
        $matchers = $this->readAttribute($mock->__phpunit_getInvocationMocker(), 'matchers');
        $this->assertTrue(is_array($matchers));

        $desc = array();
        foreach ($matchers as $matcher)
            $desc[] = $matcher->toString();

        $this->assertNotEmpty($desc);
        $this->assertEquals(array(
            'invoked 1 time(s) where method name is equal to <string:foo> and with parameter 0 is equal to <string:foo> will return the current object',
            'invoked 2 time(s) where method name is equal to <string:offsetGet> and with parameter 0 is equal to <string:foo> will return argument #0',
            'invoked 1 time(s) where method name is equal to <string:offsetExists> and with parameter 0 is equal to <string:foo> will return result of user defined callback Bar\Foo\Container->offsetGet() with the passed arguments',
        ), $desc);
    }

    public function testGetMockWithNamespacedMockClassNameForNotFoundClass()
    {
        $mock = $this->getMock($class = 'NotFoundClass', array($method = 'foo'), array(), 'NS\MockNotFound');
        $mock->expects($this->once())->method($method)->willReturnArgument(0);

        $this->assertInstanceOf($class, $mock);
        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(class_exists('NS\MockNotFound', false));

        $this->assertNull($mock->$method());
    }

    public function testGetMockWithNamespacedMockClassName()
    {
        $mock = $this->getMock(array($class = 'NS\NotFoundClass', 'ArrayAccess'), array($method = 'foo'), array(), 'Bar\Foo\MockNotFound');
        $mock->expects($this->once())->method($method)->willReturn(true);
        $mock->expects($this->once())->method('offsetGet')->with($method)->willReturnMap(array(
            array($method, true),
        ));
        $mock->expects($this->once())->method('offsetExists')->with($method)->willReturnCallback(function () {
            return false;
        });

        $this->assertInstanceOf($class, $mock);
        $this->assertInstanceOf('ArrayAccess', $mock);
        $this->assertTrue(class_exists($class, false));
        $this->assertTrue(class_exists('Bar\Foo\MockNotFound', false));

        $this->assertTrue($mock->$method($method));
        $this->assertFalse(isset($mock[$method]));
        $this->assertTrue($mock[$method]);

        // verify `toString()` methods
        $matchers = $this->readAttribute($mock->__phpunit_getInvocationMocker(), 'matchers');
        $this->assertTrue(is_array($matchers));

        $desc = array();
        foreach ($matchers as $matcher)
            $desc[] = $matcher->toString();

        $this->assertNotEmpty($desc);
        $this->assertEquals(array(
            'invoked 1 time(s) where method name is equal to <string:foo> will return user-specified value true',
            'invoked 1 time(s) where method name is equal to <string:offsetGet> and with parameter 0 is equal to <string:foo> will return value from a map',
            'invoked 1 time(s) where method name is equal to <string:offsetExists> and with parameter 0 is equal to <string:foo> will return result of user defined callback Closure with the passed arguments',
        ), $desc);
    }
}
