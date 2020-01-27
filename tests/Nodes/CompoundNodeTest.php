<?php

namespace Phi\Tests\Nodes;

use Phi\Nodes\IssetExpression;
use Phi\Token;
use PHPUnit\Framework\TestCase;

class CompoundNodeTest extends TestCase
{
    public function test_detach()
    {
        $node = new IssetExpression();
        $keyword = new Token(Token::PH_T_ISSET, 'isset');
        $node->setKeyword($keyword);

        self::assertTrue($keyword->isAttached());
        self::assertTrue($node->hasKeyword());
        self::assertSame($keyword, $node->getKeyword());

        $keyword->detach();

        self::assertFalse($keyword->isAttached());
        self::assertFalse($node->hasKeyword());
    }
}
