<?php

declare(strict_types=1);

namespace Phi;

use Phi\Exception\LiteralParsingException;

class LiteralParser
{
	/**
	 * @var int
	 * @see PhpVersion
	 */
	private $phpVersion;

	public function __construct(int $phpVersion)
	{
		PhpVersion::validate($phpVersion);
		$this->phpVersion = $phpVersion;
	}

	public function validateSingleQuotedString(string $source): void
	{
		$this->parseSingleQuotedString($source);
	}

	public function parseSingleQuotedString(string $source): string
	{
		$source = $this->trimStringDelimiters($source, '\'');
		$this->validateSingleQuotedStringContent($source);
		return \str_replace(['\\\\', '\\\''], ['\\', '\''], $source);
	}

	private function validateSingleQuotedStringContent(string $source): void
	{
		$withoutEscapes = \str_replace(['\\\\', '\\\''], ['', ''], $source);
		if (
			strpos($withoutEscapes, '\'') !== false // unescaped single quote
			|| ($withoutEscapes[-1] ?? null) === '\\' // closing quoted was escaped
		)
		{
			throw new LiteralParsingException();
		}
	}

	public function parseConstantDoubleQuotedString(string $source): string
	{
		return $this->parseStringContentEscapes($this->trimStringDelimiters($source, '"'), true);
	}

	public function parseStringContentEscapes(string $source, bool $doubleQuote): string
	{
		return \preg_replace_callback('{
			(?: \\\\ [\\\\nrtvef$"] )
			| (?: \\\\ ([0-7]{1,3}) )
			| (?: \\\\ [Xx] ([0-9A-Fa-f]{1,2}) )
			| (?: \\\\ [Uu] \{ ([0-9A-Fa-f]+) \} )
			| (?<E> ' . ($doubleQuote ? ' " | ' : '') . ' \\\\ $ )
		}xD', function ($m): string
		{
			switch ($m[0][1] ?? null)
			{
				case '\\': return '\\';
				case '"': return '"';
				case '$': return '$';
				case 'n': return "\n";
				case 'r': return "\r";
				case 't': return "\t";
				case 'f': return "\f";
				case 'v': return "\v";
				case 'e': return "\x1B";
				case '0':
				case '1':
				case '2':
				case '3':
				case '4':
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
					return \chr(\octdec($m[1]));
				case 'X':
				case 'x':
					return \chr(\hexdec($m[2]));
				case 'U':
				case 'u':
					$point = \hexdec($m[3]);
					if ($point > 0x10FFFF)
					{
						throw new LiteralParsingException();
					}
					return self::codepointToUtf8($point);
				default:
					assert(isset($m['E']));
					throw new LiteralParsingException();
			}
		}, $source);
	}

	private function trimStringDelimiters(string $source, string $delimiter): string
	{
		if ($source && ($source[0] === 'b' || $source[0] === 'B'))
		{
			$source = \substr($source, 1);
		}

		if (\strlen($source) < 2 || $source[0] !== $delimiter || $source[-1] !== $delimiter)
		{
			throw new LiteralParsingException();
		}

		return \substr($source, 1, -1);
	}

	public function validateIntegerLiteral(string $source): void
	{
		if ($this->phpVersion < PhpVersion::PHP_7_4 && \strpos($source, '_') !== false)
		{
			throw new LiteralParsingException();
		}

		$source = \preg_replace('{([0-9A-F])_(?=[0-9A-F])}i', '', $source);
		// any invalid separators will remain and cause the regex to fail

		if (!\preg_match('{^(
			0x [0-9A-F]+
			| 0b [0-1]+
			| 0 [0-7]+
			| [1-9] [0-9]*
			| 0
		)$}ixD', $source))
		{
			throw new LiteralParsingException();
		}
	}

	/**
	 * @return int|float only returns a float when the literal overflows
	 */
	public function parseIntegerLiteral(string $source)
	{
		$this->validateIntegerLiteral($source);

		$source = \str_replace('_', '', $source);
		$source = \strtolower($source);

		if ($source[0] === '0')
		{
			if (($source[1] ?? null) === 'x')
			{
				return \hexdec(\substr($source, 2));
			}
			else if (($source[1] ?? null) === 'b')
			{
				return \bindec(\substr($source, 2));
			}
			else
			{
				return \octdec(\substr($source, 1));
			}
		}
		else
		{
			return +$source; // like (int), but returns float on overflow
		}
	}

	public function validateFloatLiteral(string $source): void
	{
		if ($this->phpVersion < PhpVersion::PHP_7_4 && \strpos($source, '_') !== false)
		{
			throw new LiteralParsingException();
		}

		$source = \preg_replace('{([0-9])_(?=[0-9])}', '', $source);
		// any invalid separators will remain and cause the regex to fail

		if (!\preg_match('{^(
			(
				[0-9]+ \. [0-9]* # 4. or 4.5
				| \. [0-9]+ # .4
			)
			(
				e [+-]? [0-9]+
			)?
		)$}ixD', $source))
		{
			throw new LiteralParsingException();
		}
	}

	public function parseFloatLiteral(string $source): float
	{
		$this->validateFloatLiteral($source);
		$source = \str_replace('_', '', $source);
		return (float) $source;
	}

	private static function codepointToUtf8(int $codepoint): string
	{
		return \mb_convert_encoding(\pack("N", $codepoint), "UTF-8", "UCS-4BE");
	}
}
