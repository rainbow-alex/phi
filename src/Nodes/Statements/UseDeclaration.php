<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedUseDeclaration;

class UseDeclaration extends CompoundNode
{
    use GeneratedUseDeclaration;

    protected function extraValidation(int $flags): void
    {
        $name = $this->getName();
        if ($name->isSpecialClass() || $name->isSpecialType())
        {
            throw ValidationException::invalidNameInContext($name);
        }
    }
}
