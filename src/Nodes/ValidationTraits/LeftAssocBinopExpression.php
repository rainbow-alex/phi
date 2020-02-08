<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;

trait LeftAssocBinopExpression
{
    protected function extraValidation(int $flags): void
    {
        if ($this->getLeft()->getPrecedence() < $this->getPrecedence())
        {
            throw ValidationException::badPrecedence($this->getLeft());
        }
        if ($this->getRight()->getPrecedence() <= $this->getPrecedence())
        {
            throw ValidationException::badPrecedence($this->getRight());
        }
    }
}
