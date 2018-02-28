<?php
class Bar
{
    public function doSomethingElse()
    {
        return $this->doSomething();
    }

    protected function doSomething()
    {
        return 'result';
    }
}
