<?php

declare(strict_types=1);

namespace Phi\Nodes\Base;

use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Oop\OopMember;
use Phi\Nodes\Statement;
use Phi\Util\Console;

abstract class CompoundNode extends Node
{
    abstract protected function &getChildRef(Node $child): Node;

    protected function detachChild(Node $childToDetach): void
    {
        $ref =& $this->getChildRef($childToDetach);
        $ref = null;
    }

    public function iterTokens(): iterable
    {
        foreach ($this->getChildNodes() as $child)
        {
            yield from $child->iterTokens();
        }
    }

    final public function validate(): void
    {
        $this->_validate(0);
        parent::validate();
    }

    // usually _validate is given just one of these flags (exclusively)
    // but occasionally a combination is required, e.g.:
    // expr++ -> READ|WRITE
    // [&expr] -> READ|ALIAS_WRITE

    /**
     * @internal
     */
    public const CTX_READ = 0x01;
    /**
     * @internal
     */
    public const CTX_WRITE = 0x02;
    /**
     * @internal
     */
    public const CTX_ALIAS_WRITE = 0x04;
    /**
     * @internal
     */
    public const CTX_ALIAS_READ = 0x08;

    /**
     * this is *added* to CTX_READ by call arguments to support implicit pass by ref, e.g. foo($a[])
     *
     * @internal
     * @see ParenthesizedExpression passes this flag to its nested expression
     * @see ArrayAccessExpression ignores CTX_READ when it encounters this flag
     */
    public const CTX_READ_IMPLICIT_BY_REF = 0x10;

    /**
     * @throws ValidationException
     * @internal
     */
    abstract public function _validate(int $flags): void;

    protected function extraValidation(int $flags): void
    {
        // nop
    }

    abstract public function _autocorrect(): void;

    protected function extraAutocorrect(): void
    {
        // nop
    }

    public function toPhp(): string
    {
        $php = "";
        foreach ($this->getChildNodes() as $child)
        {
            $php .= $child;
        }
        return $php;
    }

    public function debugDump(string $indent = ""): void
    {
        $important = ($this instanceof Statement || $this instanceof Expression || $this instanceof OopMember);
        echo $indent . ($important ? Console::bold($this->repr()) : $this->repr()) . " {\n";

        foreach ((array) $this as $name => $node)
        {
            $name = \explode("\0", $name);
            $name = \end($name);
            assert($name !== false);

            if ($name === "phpVersion" || $name === "parent") continue;

            echo $indent . "    " . Console::blue($name) . ":\n";
            if ($node instanceof Node)
            {
                $node->debugDump($indent . "        ");
            }
            else
            {
                echo $indent . "    ~\n";
            }
        }

        echo $indent . "}\n";
    }
}
