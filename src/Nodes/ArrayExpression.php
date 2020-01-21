<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\SeparatedNodesList;

interface ArrayExpression extends Expression
{
    /** @return SeparatedNodesList|ArrayItem[] */
    public function getItems(): SeparatedNodesList;
}
