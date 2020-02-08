<?php

declare(strict_types=1);

namespace Phi\Nodes\Oop;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedClassConstant;
use Phi\TokenType;

class ClassConstant extends OopMember
{
    use GeneratedClassConstant;

    protected function extraValidation(int $flags): void
    {
        if ($this->getName()->getSource() === "class")
        {
            throw ValidationException::invalidSyntax($this->getName(), [TokenType::T_STRING]);
        }
    }
}
