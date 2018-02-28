<?php
class Foo
{
    public function doSomething(Bar $bar)
    {
        return $bar->doSomethingElse();
    }

    public function &doSomethingByRef(&$a = null)
    {
        return $a;
    }
}
