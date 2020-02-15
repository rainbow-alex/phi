<?php

declare(strict_types=1);

namespace Phi\Tests\Testing;

trait AssertThrows
{
	private static function assertThrows(string $class, callable $closure): \Throwable
	{
		try
		{
			$closure();
		}
		catch (\Throwable $t)
		{
			self::assertInstanceOf($class, $t);
			return $t;
		}

		self::fail("Expected throw of class " . $class . ", but nothing was thrown");
	}
}
