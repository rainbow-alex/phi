<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedSymbolBooleanAndExpression;

class SymbolBooleanAndExpression extends GeneratedSymbolBooleanAndExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
