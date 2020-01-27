<?php

namespace Phi\Exception;

use Phi\Node;

class MissingNodeException extends PhiException
{
    public function __construct(Node $node, string $method)
    {
        parent::__construct($method . ": the required node is missing", $node);
    }
}
