<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Specification;
use Phi\Optional;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsToken;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\ValidCompoundNode;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Nodes as Nodes;
use Phi\Specifications as Specs;

abstract class GeneratedMagicConstant extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'token' => new IsToken(\T_DIR, \T_FILE, \T_LINE, \T_FUNC_C, \T_CLASS_C, \T_METHOD_C),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $token;

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Token|null $token
     * @return static
     */
    public static function __instantiateUnchecked($token)
    {
        $instance = new static();
        $instance->token = $token;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'token' => &$this->token,
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
            $token = NodeConverter::convert($token, Token::class, $this->_phpVersion);
            $token->_attachTo($this);
        }
        if ($this->token !== null)
        {
            $this->token->detach();
        }
        $this->token = $token;
    }
}
