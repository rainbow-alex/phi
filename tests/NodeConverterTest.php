<?php

namespace Phi\Tests;

use Phi\NodeConverter;
use Phi\Nodes\RegularBlock;
use Phi\PhpVersion;
use Phi\Tests\Testing\TestRepr;
use PHPUnit\Framework\TestCase;

class NodeConverterTest extends TestCase
{
    public function test_convert_string_to_block()
    {
        $node = NodeConverter::convert("3", RegularBlock::class, PhpVersion::PHP_7_2);
        $this->assertSame("RegularBlock([ExpressionStatement(3)])", TestRepr::node($node));
    }
}
