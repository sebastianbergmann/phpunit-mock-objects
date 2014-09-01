<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2014, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit_MockObject
 * @author     Giorgio Sironi <piccoloprincipeazzurro@gmail.com>
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2010-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/sebastianbergmann/phpunit-mock-objects
 * @since      File available since Release 1.0.0
 */

/**
 * Implementation of the Builder pattern for Mock objects.
 *
 * @package    PHPUnit_MockObject
 * @author     Giorgio Sironi <piccoloprincipeazzurro@gmail.com>
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  2010-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://github.com/sebastianbergmann/phpunit-mock-objects
 * @since      File available since Release 1.0.0
 */
class PHPUnit_Framework_MockObject_MockBuilder
{
    /**
     * @var PHPUnit_Framework_TestCase
     */
    private $testCase;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $methods = array();

    /**
     * @var string
     */
    private $mockClassName = '';

    /**
     * @var array
     */
    private $constructorArgs = array();

    /**
     * @var boolean
     */
    private $originalConstructor = true;

    /**
     * @var boolean
     */
    private $originalClone = true;

    /**
     * @var boolean
     */
    private $autoload = true;

    /**
     * @var boolean
     */
    private $cloneArguments = false;

    /**
     * @var boolean
     */
    private $callOriginalMethods = false;

    /**
     * @var object
     */
    private $proxyTarget = null;

    /**
     * @param PHPUnit_Framework_TestCase $testCase
     * @param array|string               $type
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase, $type)
    {
        $this->testCase = $testCase;
        $this->type     = $type;
    }

    /**
     * Creates a mock object using a fluent interface.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getMock()
    {
        return $this->testCase->getMock(
          $this->type,
          $this->methods,
          $this->constructorArgs,
          $this->mockClassName,
          $this->originalConstructor,
          $this->originalClone,
          $this->autoload,
          $this->cloneArguments,
          $this->callOriginalMethods,
          $this->proxyTarget
        );
    }

    /**
     * Creates a mock object for an abstract class using a fluent interface.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockForAbstractClass()
    {
        return $this->testCase->getMockForAbstractClass(
          $this->type,
          $this->constructorArgs,
          $this->mockClassName,
          $this->originalConstructor,
          $this->originalClone,
          $this->autoload,
          $this->methods,
          $this->cloneArguments
        );
    }

    /**
     * Creates a mock object for a trait using a fluent interface.
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getMockForTrait()
    {
        return $this->testCase->getMockForTrait(
          $this->type,
          $this->constructorArgs,
          $this->mockClassName,
          $this->originalConstructor,
          $this->originalClone,
          $this->autoload,
          $this->methods,
          $this->cloneArguments
        );
    }

    /**
     * Specifies the subset of methods to mock. Default is to mock all of them.
     *
     * @param  array|null                               $methods
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Specifies the arguments for the constructor.
     *
     * @param  array                                    $args
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function setConstructorArgs(array $args)
    {
        $this->constructorArgs = $args;

        return $this;
    }

    /**
     * Specifies the name for the mock class.
     *
     * @param  string                                   $name
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function setMockClassName($name)
    {
        $this->mockClassName = $name;

        return $this;
    }

    /**
     * Disables the invocation of the original constructor.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function disableOriginalConstructor()
    {
        $this->originalConstructor = false;

        return $this;
    }

    /**
     * Enables the invocation of the original constructor.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 1.2.0
     */
    public function enableOriginalConstructor()
    {
        $this->originalConstructor = true;

        return $this;
    }

    /**
     * Disables the invocation of the original clone constructor.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function disableOriginalClone()
    {
        $this->originalClone = false;

        return $this;
    }

    /**
     * Enables the invocation of the original clone constructor.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 1.2.0
     */
    public function enableOriginalClone()
    {
        $this->originalClone = true;

        return $this;
    }

    /**
     * Disables the use of class autoloading while creating the mock object.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     */
    public function disableAutoload()
    {
        $this->autoload = false;

        return $this;
    }

    /**
     * Enables the use of class autoloading while creating the mock object.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 1.2.0
     */
    public function enableAutoload()
    {
        $this->autoload = true;

        return $this;
    }

    /**
     * Disables the cloning of arguments passed to mocked methods.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 1.2.0
     */
    public function disableArgumentCloning()
    {
        $this->cloneArguments = false;

        return $this;
    }

    /**
     * Enables the cloning of arguments passed to mocked methods.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 1.2.0
     */
    public function enableArgumentCloning()
    {
        $this->cloneArguments = true;

        return $this;
    }

    /**
     * Enables the invocation of the original methods.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 2.0.0
     */
    public function enableProxyingToOriginalMethods()
    {
        $this->callOriginalMethods = true;

        return $this;
    }

    /**
     * Disables the invocation of the original methods.
     *
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 2.0.0
     */
    public function disableProxyingToOriginalMethods()
    {
        $this->callOriginalMethods = false;
        $this->proxyTarget         = null;

        return $this;
    }

    /**
     * Sets the proxy target.
     *
     * @param  object                                   $object
     * @return PHPUnit_Framework_MockObject_MockBuilder
     * @since  Method available since Release 2.0.0
     */
    public function setProxyTarget($object)
    {
        $this->proxyTarget = $object;

        return $this;
    }
}
