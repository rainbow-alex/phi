<?php

namespace Phi\Meta;

use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Token;

class NodeDef
{
    /** @var string */
    public $className;
    /** @var string|null */
    public $extends = null;
    /** @var NodeChildDef[] */
    public $children = [];
    /** @var string[] */
    public $constructor = [];

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function withExtends(string $extends): self
    {
        assert(!$this->extends);
        $clone = clone $this;
        $clone->extends = $extends;
        return $clone;
    }

    public function withChild(string $name, string $class)
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            $class,
            null,
            null,
            null
        );
        return $clone;
    }

    public function withOptChild(string $name, string $class)
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            true,
            $class,
            null,
            null,
            null
        );
        return $clone;
    }

    public function withToken(string $name, $tokenTypes)
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            Token::class,
            null,
            ensure_array($tokenTypes),
            null
        );
        return $clone;
    }

    public function withOptToken(string $name, $tokenTypes)
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            true,
            Token::class,
            null,
            ensure_array($tokenTypes),
            null
        );
        return $clone;
    }

    public function withList(string $name, string $itemClass): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            NodesList::class,
            $itemClass,
            null,
            null
        );
        return $clone;
    }

    public function withTokenList(string $name, array $tokenTypes): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            NodesList::class,
            Token::class,
            ensure_array($tokenTypes),
            null
        );
        return $clone;
    }

    public function withSepList(string $name, string $itemClass, $separator): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            SeparatedNodesList::class,
            $itemClass,
            null,
            ensure_array($separator)
        );
        return $clone;
    }

    public function withSepTokenList(string $name, $tokenTypes, $separator): self
    {
        assert(!isset($this->children[$name]));
        $clone = clone $this;
        $clone->children[$name] = new NodeChildDef(
            $name,
            false,
            SeparatedNodesList::class,
            Token::class,
            ensure_array($tokenTypes),
            ensure_array($separator)
        );
        return $clone;
    }

    public function withConstructor(string ...$params): self
    {
        assert(!$this->constructor);
        $clone = clone $this;
        $clone->constructor = $params;
        return $clone;
    }

    public function shortClassName(): string
    {
        $parts = explode("\\", $this->className);
        return "Generated" . rtrim(array_pop($parts), "_");
    }
}
