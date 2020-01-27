<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;

abstract class Expression extends CompoundNode
{
    // usually validateContext is given just one of these flags (exclusively)
    // but occasionally a parent expression requires READ|WRITE, e.g. EXPR++
    // TODO double check which if any do READ|WRITE
    public const CTX_READ = 0x01;
    public const CTX_WRITE = 0x02;
    /** used e.g. for left-hand side of =& */
    public const CTX_ALIAS_WRITE = 0x04;
    /** used e.g. for right-hand side of =& */
    public const CTX_ALIAS_READ = 0x08;

    // this is *added* to CTX_READ by call arguments to support implicit pass by ref, e.g. foo($a[])
    /** @see ParenthesizedExpression passes this flag to its nested expression */
    /** @var ArrayAccessExpression ignores CTX_READ when it encounters this flag */
    public const CTX_READ_OR_IMPLICIT_ALIAS_READ = 0x10;
    // TODO
    // note: maybe the READ|WRITE thing was right after all...
    // NODE 2: looks like the extra flag was even better!!! TODO-OOOO

    /** convenient shorthand since most expressions reject all alias contexts the same */
    public const CTX_ALIAS = self::CTX_ALIAS_READ|self::CTX_ALIAS_WRITE;

    abstract public function validateContext(int $flags): void;
}
