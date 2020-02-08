<?php

declare(strict_types=1);

namespace Phi\Nodes\Blocks;

use Phi\Nodes\Block;
use Phi\Nodes\Generated\GeneratedImplicitBlock;

class ImplicitBlock extends Block
{
    use GeneratedImplicitBlock;

    public function getStatements(): iterable
    {
        return [$this->getStatement()];
    }
}
