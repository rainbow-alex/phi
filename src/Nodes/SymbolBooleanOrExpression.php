<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedSymbolBooleanOrExpression;

class SymbolBooleanOrExpression extends GeneratedSymbolBooleanOrExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
