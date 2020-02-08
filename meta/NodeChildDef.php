<?php

declare(strict_types=1);

namespace Phi\Meta;

use LogicException;
use Phi\Node;

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
    /** @var int|string */
    public $validationFlags = 0;

    /**
     * @param array<int>|null $tokenTypes
     * @param array<int>|null $separatorTypes
     * @param int|string $validationFlags
     */
    public function __construct(
        string $name,
        bool $optional,
        string $class,
        ?string $itemClass,
        array $tokenTypes = null,
        array $separatorTypes = null,
        $validationFlags = 0
    )
    {
        $this->name = $name;
        $this->optional = $optional;
        $this->isList = $itemClass !== null;
        $this->class = $class;
        $this->itemClass = $itemClass;
        $this->tokenTypes = $tokenTypes;
        $this->separatorTypes = $separatorTypes;
        $this->validationFlags = $validationFlags;
    }

    public function ucName(): string
    {
        return ucwords($this->name);
    }

    public function singularName(): ?string
    {
        return self::singular($this->name);
    }

    public function itemVar(): string
    {
        return "\$" . $this->singularName();
    }

    public function phpType(): string
    {
        return "\\" . $this->class;
    }

    public function docType(bool $null = true): string
    {
        if ($this->isList)
        {
            return $this->phpType() . "|\\" . $this->itemClass . "[]";
        }
        else
        {
            return $this->phpType() . ($this->optional || $null ? "|null" : "");
        }
    }

    public function phpstanDocType(): string
    {
        assert($this->isList);
        return $this->phpType() . "<\\" . $this->itemClass . ">";
    }

    public function getter(): string
    {
        return "get" . $this->ucName();
    }

    public function getterReturnType(): string
    {
        return ($this->optional ? "?" : "") . $this->phpType();
    }

    public function hasser(): string
    {
        return "has" . $this->ucName();
    }

    public function setter(): string
    {
        return "set" . $this->ucName();
    }

    public function setterDocType(): string
    {
        if ($this->isList)
        {
            throw new LogicException;
        }
        else
        {
            return $this->phpType() . "|\\" . Node::class . "|string|null";
        }
    }

    public function itemType(): string
    {
        assert($this->isList);
        return "\\" . $this->itemClass;
    }

    public function itemDocType(): string
    {
        return $this->itemType();
    }

    private static function singular(string $s): ?string
    {
        switch ($s)
        {
            case "names":
                return "name";

            default:
                if (substr($s, -3) === "ies")
                {
                    return substr($s, 0, -3) . "y";
                }
                else if (substr($s, -2) === "es")
                {
                    return substr($s, 0, -2);
                }
                else if (substr($s, -1) === "s")
                {
                    return substr($s, 0, -1);
                }
                else
                {
                    return null;
                }
        }
    }
}
