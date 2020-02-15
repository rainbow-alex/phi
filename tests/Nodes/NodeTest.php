<?php

declare(strict_types=1);

namespace Phi\Tests\Nodes;

use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\NodeAssertions;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
	use NodeAssertions;

	public function test_clone(): void
	{
		$ast = (new Parser(PhpVersion::PHP_7_2))->parse(__FILE__);

		self::assertNodeTreeIsCorrect($ast);
		$clone = $ast->clone();

		self::assertNodeTreeIsCorrect($ast);
		self::assertNodeTreeIsCorrect($clone);

		self::assertNodeStructEquals($ast, $clone);
	}
}
