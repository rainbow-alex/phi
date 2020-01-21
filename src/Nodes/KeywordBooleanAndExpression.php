<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedKeywordBooleanAndExpression;

class KeywordBooleanAndExpression extends GeneratedKeywordBooleanAndExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
