<?php

declare(strict_types=1);

namespace Phi\Util;

abstract class Util
{
	/**
	 * @template T
	 * @phpstan-param iterable<T> $it
	 * @phpstan-return array<T>
	 */
	public static function iterableToArray(iterable $it): array
	{
		if (is_array($it))
		{
			return $it;
		}
		else
		{
			/** @var \Traversable $it */
			return \iterator_to_array($it, false);
		}
	}

	public static function stringSplice(string $string, int $offset, int $length, string $replacement = ''): string
	{
		return \substr($string, 0, $offset) . $replacement . \substr($string, $offset + $length);
	}
}
