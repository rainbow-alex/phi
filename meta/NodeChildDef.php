<?php

namespace Phi\Meta;

use LogicException;
use Phi\Meta\Validators\IsTokenValidator;
use Phi\Meta\Validators\NodeValidator;
use Phi\Node;
use Phi\Token;

class NodeChildDef
{
    /** @var string */
    public $name;
    /** @var bool */
    public $optional = false;
    /** @var bool */
    public $isList = false;
    /** @var string */
    public $class;
    /** @var string|null */
    public $itemClass = null;
    /** @var array<int>|null */
    public $tokenTypes;
    /** @var array<int>|null */
    public $separatorTypes;
    /** @var NodeValidator[] */
    public $validators = [];

    /**
     * @param array<int|string>|null $tokenTypes
     * @param array<int|string>|null $separatorTypes
     */
    public function __construct(string $name, bool $optional, string $class, ?string $itemClass, array $tokenTypes = null, array $separatorTypes = null)
    {
        $this->name = $name;
        $this->optional = $optional;
        $this->isList = $itemClass !== null;
        assert(preg_match('{^Phi\\\\}', $class));
        $this->class = $class;
        assert($itemClass === null || preg_match('{^Phi\\\\}', $itemClass));
        $this->itemClass = $itemClass;
        $this->tokenTypes = $tokenTypes;
        $this->separatorTypes = $separatorTypes;

        if ($class === Token::class && $tokenTypes)
        {
            $this->validators[] = new IsTokenValidator(...$tokenTypes);
        }
    }

    public function ucName(): string
    {
        return ucwords($this->name);
    }

    public function singularName(): ?string
    {
        return singular($this->name);
    }

    public function ucSingularName(): string
    {
        return ucwords(singular($this->name));
    }

    public function itemVar(): string
    {
        return "\${$this->singularName()}";
    }

    public function phpType(): string
    {
        return imported($this->class);
    }

    public function docType(): string
    {
        if ($this->isList)
        {
            return $this->phpType() . "|" . imported($this->itemClass) . "[]";
        }
        else
        {
            return $this->phpType() . "|null";
        }
    }

    public function getter(): string
    {
        return "get{$this->ucName()}";
    }

    public function getterReturnType(): string
    {
        return ($this->optional ? '?' : '') . $this->phpType();
    }

    public function hasser(): string
    {
        return "has{$this->ucName()}";
    }

    public function setter(): string
    {
        return "set{$this->ucName()}";
    }

    public function setterDocType(): string
    {
        if ($this->isList)
        {
            throw new LogicException;
        }
        else
        {
            return $this->phpType() . "|" . imported(Node::class) . "|string|null";
        }
    }

    public function itemType(): string
    {
        assert($this->isList);
        return imported($this->itemClass);
    }

    public function itemDocType(): string
    {
        return $this->itemType();
    }

    public function adder(): string
    {
        return "add{$this->ucSingularName()}";
    }
}
