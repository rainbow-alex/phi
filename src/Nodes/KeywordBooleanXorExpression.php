<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedKeywordBooleanXorExpression;

class KeywordBooleanXorExpression extends GeneratedKeywordBooleanXorExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
