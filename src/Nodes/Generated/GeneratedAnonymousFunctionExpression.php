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

abstract class GeneratedAnonymousFunctionExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'static' => new Optional(new IsToken(\T_STATIC)),
                'keyword' => new IsToken(\T_FUNCTION),
                'leftParenthesis' => new IsToken('('),
                'parameters' => new And_(new EachItem(new IsInstanceOf(Nodes\Parameter::class)), new EachSeparator(new IsToken(','))),
                'rightParenthesis' => new IsToken(')'),
                'use' => new Optional(new Any),
                'returnType' => new Optional(new Any),
                'body' => new Any,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $static;
    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Token|null
     */
    private $leftParenthesis;
    /**
     * @var SeparatedNodesList|Nodes\Parameter[]
     */
    private $parameters;
    /**
     * @var Token|null
     */
    private $rightParenthesis;
    /**
     * @var Nodes\AnonymousFunctionUse|null
     */
    private $use;
    /**
     * @var Nodes\ReturnType|null
     */
    private $returnType;
    /**
     * @var Nodes\Block|null
     */
    private $body;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->parameters = new SeparatedNodesList();
    }

    /**
     * @param Token|null $static
     * @param Token|null $keyword
     * @param Token|null $leftParenthesis
     * @param mixed[] $parameters
     * @param Token|null $rightParenthesis
     * @param Nodes\AnonymousFunctionUse|null $use
     * @param Nodes\ReturnType|null $returnType
     * @param Nodes\Block|null $body
     * @return static
     */
    public static function __instantiateUnchecked($static, $keyword, $leftParenthesis, $parameters, $rightParenthesis, $use, $returnType, $body)
    {
        $instance = new static();
        $instance->static = $static;
        $instance->keyword = $keyword;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->parameters->__initUnchecked($parameters);
        $instance->rightParenthesis = $rightParenthesis;
        $instance->use = $use;
        $instance->returnType = $returnType;
        $instance->body = $body;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'static' => &$this->static,
            'keyword' => &$this->keyword,
            'leftParenthesis' => &$this->leftParenthesis,
            'parameters' => &$this->parameters,
            'rightParenthesis' => &$this->rightParenthesis,
            'use' => &$this->use,
            'returnType' => &$this->returnType,
            'body' => &$this->body,
        ];
        return $refs;
    }

    public function getStatic(): ?Token
    {
        return $this->static;
    }

    public function hasStatic(): bool
    {
        return $this->static !== null;
    }

    /**
     * @param Token|Node|string|null $static
     */
    public function setStatic($static): void
    {
        if ($static !== null)
        {
            /** @var Token $static */
            $static = NodeConverter::convert($static, Token::class, $this->_phpVersion);
            $static->_attachTo($this);
        }
        if ($this->static !== null)
        {
            $this->static->detach();
        }
        $this->static = $static;
    }

    public function getKeyword(): Token
    {
        if ($this->keyword === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param Token|Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var Token $keyword */
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getLeftParenthesis(): Token
    {
        if ($this->leftParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var Token $leftParenthesis */
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->_phpVersion);
            $leftParenthesis->_attachTo($this);
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    /**
     * @return SeparatedNodesList|Nodes\Parameter[]
     */
    public function getParameters(): SeparatedNodesList
    {
        return $this->parameters;
    }

    /**
     * @param Nodes\Parameter $parameter
     */
    public function addParameter($parameter): void
    {
        /** @var Nodes\Parameter $parameter */
        $parameter = NodeConverter::convert($parameter, Nodes\Parameter::class);
        $this->parameters->add($parameter);
    }

    public function getRightParenthesis(): Token
    {
        if ($this->rightParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var Token $rightParenthesis */
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->_phpVersion);
            $rightParenthesis->_attachTo($this);
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function getUse(): ?Nodes\AnonymousFunctionUse
    {
        return $this->use;
    }

    public function hasUse(): bool
    {
        return $this->use !== null;
    }

    /**
     * @param Nodes\AnonymousFunctionUse|Node|string|null $use
     */
    public function setUse($use): void
    {
        if ($use !== null)
        {
            /** @var Nodes\AnonymousFunctionUse $use */
            $use = NodeConverter::convert($use, Nodes\AnonymousFunctionUse::class, $this->_phpVersion);
            $use->_attachTo($this);
        }
        if ($this->use !== null)
        {
            $this->use->detach();
        }
        $this->use = $use;
    }

    public function getReturnType(): ?Nodes\ReturnType
    {
        return $this->returnType;
    }

    public function hasReturnType(): bool
    {
        return $this->returnType !== null;
    }

    /**
     * @param Nodes\ReturnType|Node|string|null $returnType
     */
    public function setReturnType($returnType): void
    {
        if ($returnType !== null)
        {
            /** @var Nodes\ReturnType $returnType */
            $returnType = NodeConverter::convert($returnType, Nodes\ReturnType::class, $this->_phpVersion);
            $returnType->_attachTo($this);
        }
        if ($this->returnType !== null)
        {
            $this->returnType->detach();
        }
        $this->returnType = $returnType;
    }

    public function getBody(): Nodes\Block
    {
        if ($this->body === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->body;
    }

    public function hasBody(): bool
    {
        return $this->body !== null;
    }

    /**
     * @param Nodes\Block|Node|string|null $body
     */
    public function setBody($body): void
    {
        if ($body !== null)
        {
            /** @var Nodes\Block $body */
            $body = NodeConverter::convert($body, Nodes\Block::class, $this->_phpVersion);
            $body->_attachTo($this);
        }
        if ($this->body !== null)
        {
            $this->body->detach();
        }
        $this->body = $body;
    }
}
