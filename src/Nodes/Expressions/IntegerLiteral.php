<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedIntegerLiteral;
use PhpParser\Node\Scalar\LNumber;

class IntegerLiteral extends NumberLiteral
{
    use GeneratedIntegerLiteral;

    protected function extraValidation(int $flags): void
    {
        if (\preg_match('{^0.*9}', $this->getToken()->getSource()))
        {
            throw ValidationException::invalidSyntax($this);
        }
    }

    public function getValue(): int
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }

    public function convertToPhpParserNode()
    {
        return new LNumber($this->getValue());
    }
}
