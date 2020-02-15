<?php

declare(strict_types=1);

namespace Phi\Tests;

use Phi\Parser;
use Phi\PhpVersion;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class SystemTest extends TestCase
{
	/** @dataProvider files_to_parse */
	public function test_parse_self(string $path): void
	{
		$source = \file_get_contents($path);

		$ast = (new Parser(PhpVersion::PHP_7_2))->parse($path, $source);
		$ast->validate();

		self::assertSame($source, (string) $ast);
	}

	public function files_to_parse()
	{
		$files = (new Finder())->in(__DIR__ . "/../src")->filter(function (\SplFileInfo $p)
		{
			return $p->getExtension() === "php";
		});

		/** @var SplFileInfo $file */
		foreach ($files as $file)
		{
			yield $file->getRelativePathname() => [$file->getRealPath()];
		}
	}
}
