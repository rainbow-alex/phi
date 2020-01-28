<?php

namespace Phi\Nodes\Base;

use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\Statement;
use Phi\Util\Console;

abstract class CompoundNode extends Node
{
    /**
     * @return array<Node|null>
     * @internal
     */
    abstract protected function &_getNodeRefs(): array;

    protected function detachChild(Node $childToDetach): void
    {
        foreach ($this->_getNodeRefs() as &$child)
        {
            if ($child === $childToDetach)
            {
                $child = null;
                return;
            }
        }

        throw new \RuntimeException($childToDetach . " is not attached to " . $this);
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter($this->_getNodeRefs()));
    }

    public function iterTokens(): iterable
    {
        foreach ($this->_getNodeRefs() as $node)
        {
            if ($node)
            {
                yield from $node->tokens();
            }
        }
    }

    public function toPhp(): string
    {
        $php = "";
        foreach ($this->_getNodeRefs() as $child)
        {
            if ($child)
            {
                $php .= $child;
            }
        }
        return $php;
    }

    public function debugDump(string $indent = ""): void
    {
        $important = ($this instanceof Statement || $this instanceof Expression || $this instanceof ClassLikeMember);
        echo $indent . ($important ? Console::bold($this->repr()) : $this->repr()) . " {\n";

        foreach ($this->_getNodeRefs() as $node)
        {
            $name = \explode("\0", \array_search($node, (array) $this));
            $name = \end($name);

            echo $indent . "  " . Console::blue($name) . ":\n";
            if ($node instanceof Node)
            {
                $node->debugDump($indent . "    ");
            }
            else
            {
                echo $indent . "    ~\n";
            }
        }

        echo $indent . "}\n";
    }
}
