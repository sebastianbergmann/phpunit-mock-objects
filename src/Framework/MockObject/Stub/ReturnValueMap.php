<?php
/*
 * This file is part of the PHPUnit_MockObject package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Stubs a method by returning a value from a map.
 *
 * @since Class available since Release 1.1.0
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

            $return = array_pop($map);
            if ($this->compare($invocation->parameters, $map)) {
                return $return;
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
}
