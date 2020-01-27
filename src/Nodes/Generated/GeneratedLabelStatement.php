<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Exception\ValidationException;
use Phi\Nodes as Nodes;

abstract class GeneratedLabelStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $label;

    /**
     * @var Token|null
     */
    private $colon;

    /**
     * @param Token|Node|string|null $label
     */
    public function __construct($label = null)
    {
        if ($label !== null)
        {
            $this->setLabel($label);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $label
     * @param Token|null $colon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $label, $colon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->label = $label;
        $instance->label->parent = $instance;
        $instance->colon = $colon;
        $instance->colon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "label" => &$this->label,
            "colon" => &$this->colon,
        ];
        return $refs;
    }

    public function getLabel(): Token
    {
        if ($this->label === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->label;
    }

    public function hasLabel(): bool
    {
        return $this->label !== null;
    }

    /**
     * @param Token|Node|string|null $label
     */
    public function setLabel($label): void
    {
        if ($label !== null)
        {
            /** @var Token $label */
            $label = NodeConverter::convert($label, Token::class, $this->phpVersion);
            $label->detach();
            $label->parent = $this;
        }
        if ($this->label !== null)
        {
            $this->label->detach();
        }
        $this->label = $label;
    }

    public function getColon(): Token
    {
        if ($this->colon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->colon;
    }

    public function hasColon(): bool
    {
        return $this->colon !== null;
    }

    /**
     * @param Token|Node|string|null $colon
     */
    public function setColon($colon): void
    {
        if ($colon !== null)
        {
            /** @var Token $colon */
            $colon = NodeConverter::convert($colon, Token::class, $this->phpVersion);
            $colon->detach();
            $colon->parent = $this;
        }
        if ($this->colon !== null)
        {
            $this->colon->detach();
        }
        $this->colon = $colon;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->label === null) throw ValidationException::childRequired($this, "label");
            if ($this->colon === null) throw ValidationException::childRequired($this, "colon");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
