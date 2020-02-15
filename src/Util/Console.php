<?php

declare(strict_types=1);

namespace Phi\Util;

class Console
{
	public static function bold(string $s): string
	{
		return self::useColors() ? "\e[1m" . $s . "\e[0m" : $s;
	}

	public static function faint(string $s): string
	{
		return self::useColors() ? "\e[2m" . $s . "\e[0m" : $s;
	}

	public static function invert(string $s): string
	{
		return self::useColors() ? "\e[7m" . $s . "\e[0m" : $s;
	}

	public static function blue(string $s): string
	{
		return self::useColors() ? "\e[34m" . $s . "\e[0m" : $s;
	}

	public static function yellow(string $s): string
	{
		return self::useColors() ? "\e[33m" . $s . "\e[0m" : $s;
	}

	public static function red(string $s): string
	{
		return self::useColors() ? "\e[31m" . $s . "\e[0m" : $s;
	}

	public static function green(string $s): string
	{
		return self::useColors() ? "\e[32m" . $s . "\e[0m" : $s;
	}

	private static function useColors(): bool
	{
		/** @noinspection PhpComposerExtensionStubsInspection */
		return \getenv("PHI_DEBUG_COLOR") || (function_exists("posix_isatty") && \posix_isatty(\STDOUT));
	}
}
