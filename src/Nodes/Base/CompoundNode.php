<?php

namespace Phi\Nodes\Base;

use Phi\Node;
use Phi\Nodes\ClassLikeMember;
use Phi\Nodes\Expression;
use Phi\Nodes\Statement;
use Phi\Util\Console;

abstract class CompoundNode extends AbstractNode
{
    /**
     * @return array<Node|null>
     * @internal
     */
    abstract public function &_getNodeRefs(): array;

    public function _detachChild(Node $childToDetach): void
    {
        foreach ($this->_getNodeRefs() as &$child)
        {
            if ($child === $childToDetach)
            {
                $child = null;
                return;
            }
        }

        throw new \RuntimeException("$childToDetach is not attached to $this");
    }

    public function childNodes(): array
    {
        return \array_values(\array_filter($this->_getNodeRefs()));
    }

    public function tokens(): iterable
    {
        foreach ($this->_getNodeRefs() as $node)
        {
            if ($node)
            {
                yield from $node->tokens();
            }
        }
    }

    public function getLeftWhitespace(): string
    {
        $firstNode = $this->firstNode();
        return $firstNode ? $firstNode->getLeftWhitespace() : '';
    }

    public function getRightWhitespace(): string
    {
        $lastNode = $this->lastNode();
        return $lastNode ? $lastNode->getRightWhitespace() : '';
    }

    public function __toString(): string
    {
        $php = '';
        foreach ($this->_getNodeRefs() as $child)
        {
            if ($child)
            {
                $php .= $child;
            }
        }
        return $php;
    }

    public function debugDump(string $indent = ''): void
    {
        $important = ($this instanceof Statement || $this instanceof Expression || $this instanceof ClassLikeMember);
        echo $indent . ($important ? Console::bold($this->repr()) : $this->repr()) . " {\n";

        foreach ($this->_getNodeRefs() as $k => $v)
        {
            echo $indent . "  " . Console::blue($k) . ":\n";
            if ($v instanceof Node)
            {
                $v->debugDump($indent . "    ");
            }
            else
            {
                echo $indent . "    ~\n";
            }
        }

        echo $indent . "}\n";
    }

    private function firstNode(): ?Node
    {
        foreach ($this->_getNodeRefs() as $node)
        {
            if ($node)
            {
                return $node;
            }
        }
        return null;
    }

    private function lastNode(): ?Node
    {
        $lastNode = null;
        foreach ($this->_getNodeRefs() as $node)
        {
            $lastNode = $node ?? $lastNode;
        }
        return $lastNode;
    }
}
