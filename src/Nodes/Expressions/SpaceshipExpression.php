<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSpaceshipExpression;
use PhpParser\Node\Expr\BinaryOp\Spaceship;

class SpaceshipExpression extends BinopExpression
{
    use GeneratedSpaceshipExpression;

    public function convertToPhpParserNode()
    {
        return new Spaceship($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
