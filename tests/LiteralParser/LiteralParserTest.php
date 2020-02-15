<?php

declare(strict_types=1);

namespace Phi\Tests\LiteralParser;

use Phi\Exception\LiteralParsingException;
use Phi\LiteralParser;
use Phi\PhpVersion;
use PHPUnit\Framework\TestCase;

class LiteralParserTest extends TestCase
{
	/** @dataProvider singleQuotedStringCases */
	public function test_parse_single_quoted_string(string $source, string $value): void
	{
		$parser = new LiteralParser(PhpVersion::PHP_7_2);

		try
		{
			$parsed = $parser->parseSingleQuotedString($source);
		}
		catch (LiteralParsingException $e)
		{
			self::assertSame('ERROR', $value);
			return;
		}

		self::assertSame($value, $parsed, "Incorrectly parsed: $source");
	}

	public function singleQuotedStringCases(): iterable
	{
		return self::parseCases(__DIR__ . "/data/single_quoted_strings.txt");
	}

	/** @dataProvider doubleQuotedStringCases */
	public function test_parse_double_quoted_string(string $source, string $value): void
	{
		$parser = new LiteralParser(PhpVersion::PHP_7_2);

		try
		{
			$parsed = $parser->parseConstantDoubleQuotedString($source);
			self::assertSame($value, eval('return ' . $source . ';'));
		}
		catch (LiteralParsingException $e)
		{
			self::assertSame('ERROR', $value);
			return;
		}

		self::assertSame($value, $parsed, "Incorrectly parsed: $source");
	}

	public function doubleQuotedStringCases(): iterable
	{
		return self::parseCases(__DIR__ . "/data/double_quoted_strings.txt");
	}

	private static function parseCases(string $file): iterable
	{
		foreach (\array_filter(\explode("\n", \file_get_contents($file))) as $case)
		{
			$case = \preg_replace_callback('{<([0-9A-F]{2}( [0-9A-F]{2})*)>}', function ($m)
			{
				$hex = \str_replace(" ", "", $m[1]);
				$decoded = "";
				for ($i = 0; $i < \strlen($hex); $i += 2)
				{
					$decoded .= \chr(\hexdec(\substr($hex, $i, 2)));
				}
				return $decoded;
			}, $case);

			[$source, $value] = \explode(" === ", $case);

			if ($value === "EMPTY")
			{
				$value = "";
			}

			yield [$source, $value];
		}
	}
}
