<?php

declare(strict_types=1);

namespace Phi;

class PhpVersion
{
	public const PHP_7_2 = 70200;
	public const PHP_7_3 = 70300;
	public const PHP_7_4 = 70400;

	private const PHP_NEXT = 80000;

	public static function validate(int $phpVersion): void
	{
		if ($phpVersion < self::PHP_7_2 || $phpVersion >= self::PHP_NEXT)
		{
			throw new \InvalidArgumentException('Unsupported PHP version: ' . $phpVersion);
		}
	}
}
