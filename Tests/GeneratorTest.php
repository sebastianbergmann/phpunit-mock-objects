<?php
class Framework_MockObject_GeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     * @expectedException PHPUnit_Framework_Exception
     */
    public function testGetMockFailsWhenInvalidFunctionNameIsPassedInAsAFunctionToMock()
    {
        PHPUnit_Framework_MockObject_Generator::getMock('stdClass', array(0));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMock
     */
    public function testGetMockCanCreateNonExistingFunctions()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getMock('stdClass', array('testFunction'));
        $this->assertTrue(method_exists($mock, 'testFunction'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassDoesNotFailWhenFakingInterfaces()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass('Countable');
        $this->assertTrue(method_exists($mock, 'count'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     */
    public function testGetMockForAbstractClassStubbingAbstractClass()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass('AbstractMockTestClass');
        $this->assertTrue(method_exists($mock, 'doSomething'));
    }

    /**
     * @dataProvider getMockForAbstractClassExpectsInvalidArgumentExceptionDataprovider
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     * @expectedException InvalidArgumentException
     */
    public function testGetMockForAbstractClassExpectingInvalidArgumentException($className, $mockClassName)
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass($className, array(), $mockClassName);
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass
     * @expectedException PHPUnit_Framework_Exception
     */
    public function testGetMockForAbstractClassAnstractClassDoesNotExist()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getMockForAbstractClass('Tux');
    }

    /**
     * Dataprovider for test "testGetMockForAbstractClassExpectingInvalidArgumentException"
     */
    public static function getMockForAbstractClassExpectsInvalidArgumentExceptionDataprovider()
    {
        return array(
            'className not a string' => array(array(), ''),
            'mockClassName not a string' => array('Countable', new stdClass()),
        );
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getFunctionMock
     * @covers PHPUnit_Framework_MockObject_Generator::generateMockedFunctionDefinitionFromExisting
     * @covers PHPUnit_Framework_MockObject_Generator::generateMockedFunctionDefinition
     */
    public function testGetFunctionMockCreatesFunction()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getFunctionMock('fopen', 'PHPUnit\\Framework\\MockObject');
        $this->assertTrue(function_exists('PHPUnit\\Framework\\MockObject\\fopen'));
    }

    /**
     * @covers PHPUnit_Framework_MockObject_Generator::getFunctionMock
     * @covers PHPUnit_Framework_MockObject_MockFunction::__construct
     * @covers PHPUnit_Framework_MockObject_MockFunction::getInstance
     */
    public function testGetFunctionMockProvidesStaticAccessToMockFunction()
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getFunctionMock('fopen', 'PHPUnit\\Framework\\MockObject');
        $this->assertEquals($mock, PHPUnit_Framework_MockObject_MockFunction::getInstance('fopen'));
    }

    /**
     * Dataprovider for test "testGetMockForAbstractClassExpectingInvalidArgumentException"
     */
    public static function getMockForFunctionExpectsInvalidArgumentExceptionDataprovider()
    {
        return array(
            'functionName not a string' => array(array(), ''),
            'targetNamespace not a string' => array('fopen', new stdClass()),
        );
    }

    /**
     * @dataProvider getMockForFunctionExpectsInvalidArgumentExceptionDataprovider
     * @covers PHPUnit_Framework_MockObject_Generator::getFunctionMock
     * @expectedException InvalidArgumentException
     */
    public function testGetMockForFunctionExpectingInvalidArgumentException($functionName, $targetNamespace)
    {
        $mock = PHPUnit_Framework_MockObject_Generator::getFunctionMock($functionName, $targetNamespace);
    }
}
