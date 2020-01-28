<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRegularName;
use Phi\TokenType;
use PhpParser\Node\Name\FullyQualified;

class RegularName extends GeneratedRegularName
{
    public function isAbsolute(): bool
    {
        $firstToken = $this->getParts()->getFirstToken();
        return $firstToken && $firstToken->getType() === TokenType::T_NS_SEPARATOR;
    }

    public function convertToPhpParserNode()
    {
        if ($this->isAbsolute())
        {
            return new FullyQualified($this->getParts()->convertToPhpParserNode());
        }
        else
        {
            return new \PhpParser\Node\Name($this->getParts()->convertToPhpParserNode());
        }
    }
}
