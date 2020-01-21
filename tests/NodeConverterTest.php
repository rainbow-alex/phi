<?php

namespace Phi\Tests;

use Phi\NodeConverter;
use Phi\Nodes\Block;
use Phi\PhpVersion;
use Phi\Tests\Testing\Stringify;
use PHPUnit\Framework\TestCase;

class NodeConverterTest extends TestCase
{
    public function test_convert_string_to_block()
    {
        $node = NodeConverter::convert('3', Block::class, PhpVersion::PHP_7_2);
        $this->assertSame('Block([ExpressionStatement(3)])', Stringify::node($node));
    }
}
