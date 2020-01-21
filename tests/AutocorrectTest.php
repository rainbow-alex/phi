<?php

namespace Phi\Tests;

use Phi\Nodes\Block;
use Phi\Nodes\FunctionStatement;
use Phi\Tests\Testing\Stringify;
use PHPUnit\Framework\TestCase;

class AutocorrectTest extends TestCase
{
    public function test_create_token(): void
    {
        $node = new Block();
        $this->assertSame('Block([])', Stringify::node($node));
        $node = $node->autocorrect();
        $this->assertSame('Block({, [], })', Stringify::node($node));
    }

    public function test_nested(): void
    {
        $node = new FunctionStatement();
        $node->setName('foo');
        $node->setBody('return something()');
        $node->autocorrect();
        $node->validate();
        $this->assertSame('function foo(){return something()}', $node->__toString());
    }
}
