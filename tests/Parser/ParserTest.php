<?php

declare(strict_types=1);

namespace Phi\Tests\Parser;

use Phi\Exception\ValidationException;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Tests\Testing\AssertThrows;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    use AssertThrows;

    public function test_parse_expression()
    {
        $parser = new Parser(PhpVersion::PHP_7_2);

        // can parse write expression in isolation
        $parser->parseExpression('list($v)');

        self::assertTrue(true);
    }
}
