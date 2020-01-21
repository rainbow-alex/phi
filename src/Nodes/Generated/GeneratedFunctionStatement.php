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

abstract class GeneratedFunctionStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_FUNCTION),
                'byReference' => new Optional(new IsToken('&')),
                'name' => new IsToken(\T_STRING),
                'leftParenthesis' => new IsToken('('),
                'parameters' => new And_(new EachItem(new IsInstanceOf(Nodes\Parameter::class)), new EachSeparator(new IsToken(','))),
                'rightParenthesis' => new IsToken(')'),
                'returnType' => new Optional(new Any),
                'body' => new Any,
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Token|null
     */
    private $byReference;
    /**
     * @var Token|null
     */
    private $name;
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
     * @param Token|null $keyword
     * @param Token|null $byReference
     * @param Token|null $name
     * @param Token|null $leftParenthesis
     * @param mixed[] $parameters
     * @param Token|null $rightParenthesis
     * @param Nodes\ReturnType|null $returnType
     * @param Nodes\Block|null $body
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $byReference, $name, $leftParenthesis, $parameters, $rightParenthesis, $returnType, $body)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->byReference = $byReference;
        $instance->name = $name;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->parameters->__initUnchecked($parameters);
        $instance->rightParenthesis = $rightParenthesis;
        $instance->returnType = $returnType;
        $instance->body = $body;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'byReference' => &$this->byReference,
            'name' => &$this->name,
            'leftParenthesis' => &$this->leftParenthesis,
            'parameters' => &$this->parameters,
            'rightParenthesis' => &$this->rightParenthesis,
            'returnType' => &$this->returnType,
            'body' => &$this->body,
        ];
        return $refs;
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

    public function getByReference(): ?Token
    {
        return $this->byReference;
    }

    public function hasByReference(): bool
    {
        return $this->byReference !== null;
    }

    /**
     * @param Token|Node|string|null $byReference
     */
    public function setByReference($byReference): void
    {
        if ($byReference !== null)
        {
            /** @var Token $byReference */
            $byReference = NodeConverter::convert($byReference, Token::class, $this->_phpVersion);
            $byReference->_attachTo($this);
        }
        if ($this->byReference !== null)
        {
            $this->byReference->detach();
        }
        $this->byReference = $byReference;
    }

    public function getName(): Token
    {
        if ($this->name === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param Token|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Token $name */
            $name = NodeConverter::convert($name, Token::class, $this->_phpVersion);
            $name->_attachTo($this);
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
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
