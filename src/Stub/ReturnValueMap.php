<?php
/*
 * This file is part of the phpunit-mock-objects package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Stubs a method by returning a value from a map.
 */
class PHPUnit_Framework_MockObject_Stub_ReturnValueMap implements PHPUnit_Framework_MockObject_Stub
{
    protected $valueMap;

    public function __construct(array $valueMap)
    {
        $this->valueMap = $valueMap;
    }

    public function invoke(PHPUnit_Framework_MockObject_Invocation $invocation)
    {
        $parameterCount = count($invocation->parameters);

        foreach ($this->valueMap as $map) {
            if (!is_array($map) || $parameterCount != count($map) - 1) {
                continue;
            }

            $returnValue = $this->getReturnValue(array_pop($map));
            if ($this->compare($invocation->parameters, $map)) {
                return $returnValue->invoke($invocation);
            }
        }

        return;
    }

    public function toString()
    {
        return 'return value from a map';
    }

    /**
     * @param  array $actual
     * @param  array $expected
     * @return bool
     */
    protected function compare($actual, $expected) {
        foreach ($expected as $index => $value) {
            if ($value instanceof PHPUnit_Framework_Constraint) {
                if ($value->evaluate($actual[$index], '', true) === false) {
                    return false;
                }
            } else {
                if ($value !== $actual[$index]) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function getReturnValue($value) {
      if (!$value instanceOf PHPUnit_Framework_MockObject_Stub) {
        return new PHPUnit_Framework_MockObject_Stub_Return($value);
      }
      return $value;
    }
}
