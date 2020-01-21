<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedKeywordBooleanOrExpression;

class KeywordBooleanOrExpression extends GeneratedKeywordBooleanOrExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
