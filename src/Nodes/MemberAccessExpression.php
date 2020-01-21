<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadWriteExpression;
use Phi\Nodes\Generated\GeneratedMemberAccessExpression;

class MemberAccessExpression extends GeneratedMemberAccessExpression
{
    use DynamicExpression;
    use ReadWriteExpression;
}
