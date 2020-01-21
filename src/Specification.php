<?php

namespace Phi;

use Phi\Exception\ValidationException;
use ReflectionClass;

abstract class Specification
{
    abstract public function isSatisfiedBy(Node $node): bool;

    public function validate(Node $node): void
    {
        if (!$this->isSatisfiedBy($node))
        {
            throw new ValidationException($this->validationErrorMessage($node), $node);
        }
    }

    protected function validationErrorMessage(Node $node): string
    {
        return $node->repr() . ' does not satisfy ' . (new ReflectionClass($this))->getShortName();
    }

    public function autocorrect(?Node $node): ?Node
    {
        return $node;
    }
}
