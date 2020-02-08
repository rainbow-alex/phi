<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;

trait UnaryOpExpression
{
    protected function extraValidation(int $flags): void
    {
        if ($this->getExpression()->getPrecedence() < $this->getPrecedence())
        {
            throw ValidationException::badPrecedence($this->getExpression());
        }
    }
}
