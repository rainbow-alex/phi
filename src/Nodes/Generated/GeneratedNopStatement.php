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

abstract class GeneratedNopStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->semiColon = $semiColon;
        $instance->semiColon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "semiColon" => &$this->semiColon,
        ];
        return $refs;
    }

    public function getSemiColon(): Token
    {
        if ($this->semiColon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param Token|Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var Token $semiColon */
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->phpVersion);
            $semiColon->detach();
            $semiColon->parent = $this;
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->semiColon === null) throw ValidationException::childRequired($this, "semiColon");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
