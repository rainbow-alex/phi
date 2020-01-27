<?php

namespace Phi\Meta\Validators;

use Phi\Node;

class ExpressionContextValidator implements NodeValidator
{
    /** @var int */
    private $flag;

    public function __construct(int $flag)
    {
        $this->flag = $flag;
    }

    public function getFlag(): int
    {
        return Node::VALIDATE_EXPRESSION_CONTEXT;
    }

    public function validate(string $var): string
    {
        // TODO: Implement validate() method.
    }
}
