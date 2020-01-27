<?php

namespace Phi\Tests\Testing;

trait AssertThrows
{
    protected function assertThrows(string $class, callable $closure): \Throwable
    {
        try
        {
            $closure();
        }
        catch (\Throwable $t)
        {
            $this->assertInstanceOf($class, $t);
            return $t;
        }

        $this->fail("Expected throw of class " . $class . ", but nothing was thrown");
    }
}
