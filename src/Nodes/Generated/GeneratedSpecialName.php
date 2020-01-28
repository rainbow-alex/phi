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

abstract class GeneratedSpecialName extends Nodes\Name
{
    /**
     * @var Token|null
     */
    private $token;


    /**
     * @param Token|Node|string|null $token
     */
    public function __construct($token = null)
    {
        if ($token !== null)
        {
            $this->setToken($token);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $token
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $token)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->token = $token;
        $token->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "token" => &$this->token,
        ];
        return $refs;
    }

    public function getToken(): Token
    {
        if ($this->token === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->token;
    }

    public function hasToken(): bool
    {
        return $this->token !== null;
    }

    /**
     * @param Token|Node|string|null $token
     */
    public function setToken($token): void
    {
        if ($token !== null)
        {
            /** @var Token $token */
            $token = NodeConverter::convert($token, Token::class, $this->phpVersion);
            $token->detach();
            $token->parent = $this;
        }
        if ($this->token !== null)
        {
            $this->token->detach();
        }
        $this->token = $token;
    }

    protected function _validate(int $flags): void
    {
        if ($this->token === null) throw ValidationException::childRequired($this, "token");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
