<?php

declare(strict_types=1);

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;

abstract class Expression extends CompoundNode
{
    public const PRECEDENCE_CLONE = 70;
    public const PRECEDENCE_POW = 62;
    public const PRECEDENCE_CAST = 61;
    public const PRECEDENCE_INSTANCEOF = 60;
    public const PRECEDENCE_BOOLEAN_NOT = 50;
    public const PRECEDENCE_MUL = 49;
    public const PRECEDENCE_ADD = 48;
    public const PRECEDENCE_SHIFT = 47;
    public const PRECEDENCE_COMPARISON2 = 37;
    public const PRECEDENCE_COMPARISON1 = 36;
    public const PRECEDENCE_BITWISE_AND = 35;
    public const PRECEDENCE_BITWISE_XOR = 34;
    public const PRECEDENCE_BITWISE_OR = 33;
    public const PRECEDENCE_SYMBOL_AND = 32;
    public const PRECEDENCE_SYMBOL_OR = 31;
    public const PRECEDENCE_COALESCE = 26;
    public const PRECEDENCE_TERNARY = 25;
    public const PRECEDENCE_YIELD = 24;
    public const PRECEDENCE_KEYWORD_AND = 13;
    public const PRECEDENCE_KEYWORD_XOR = 12;
    public const PRECEDENCE_KEYWORD_OR = 11;

    public function isConstant(): bool
    {
        return false;
    }

    public function isTemporary(): bool
    {
        return true;
    }

    public function getPrecedence(): int
    {
        return 99; // TODO needs to be implemented for all expressions
    }
}
