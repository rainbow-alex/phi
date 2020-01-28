<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;

abstract class Expression extends CompoundNode
{
    // usually validateContext is given just one of these flags (exclusively)
    // but occasionally a combination is required, e.g.:
    // expr++ -> READ|WRITE
    // [&expr] -> READ|ALIAS_WRITE
    public const CTX_READ = 0x01;
    public const CTX_WRITE = 0x02;
    /** used e.g. for left-hand side of =& */
    public const CTX_ALIAS_WRITE = 0x04;
    /** used e.g. for right-hand side of =& */
    public const CTX_ALIAS_READ = 0x08;

    // this is *added* to CTX_READ by call arguments to support implicit pass by ref, e.g. foo($a[])
    /** @see ParenthesizedExpression passes this flag to its nested expression */
    /** @var ArrayAccessExpression ignores CTX_READ when it encounters this flag */
    public const CTX_READ_IMPLICIT_BY_REF = 0x10;

    /** convenient shorthand since most expressions reject all alias contexts the same */
    public const CTX_ALIAS = self::CTX_ALIAS_READ|self::CTX_ALIAS_WRITE;

    abstract public function validateContext(int $flags): void;
}
