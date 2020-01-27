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

abstract class GeneratedFunctionStatement extends Nodes\Statement
{
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
     * @var Nodes\RegularBlock|null
     */
    private $body;

    /**
     */
    public function __construct()
    {
        $this->parameters = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Token|null $byReference
     * @param Token|null $name
     * @param Token|null $leftParenthesis
     * @param mixed[] $parameters
     * @param Token|null $rightParenthesis
     * @param Nodes\ReturnType|null $returnType
     * @param Nodes\RegularBlock|null $body
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $byReference, $name, $leftParenthesis, $parameters, $rightParenthesis, $returnType, $body)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference)
        {
            $instance->byReference->parent = $instance;
        }
        $instance->name = $name;
        $instance->name->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->leftParenthesis->parent = $instance;
        $instance->parameters->__initUnchecked($parameters);
        $instance->parameters->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->rightParenthesis->parent = $instance;
        $instance->returnType = $returnType;
        if ($returnType)
        {
            $instance->returnType->parent = $instance;
        }
        $instance->body = $body;
        $instance->body->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
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
            $byReference = NodeConverter::convert($byReference, Token::class, $this->phpVersion);
            $byReference->detach();
            $byReference->parent = $this;
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
            $name = NodeConverter::convert($name, Token::class, $this->phpVersion);
            $name->detach();
            $name->parent = $this;
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
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->phpVersion);
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
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
        $parameter = NodeConverter::convert($parameter, Nodes\Parameter::class, $this->phpVersion);
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
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->phpVersion);
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
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
            $returnType = NodeConverter::convert($returnType, Nodes\ReturnType::class, $this->phpVersion);
            $returnType->detach();
            $returnType->parent = $this;
        }
        if ($this->returnType !== null)
        {
            $this->returnType->detach();
        }
        $this->returnType = $returnType;
    }

    public function getBody(): Nodes\RegularBlock
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
     * @param Nodes\RegularBlock|Node|string|null $body
     */
    public function setBody($body): void
    {
        if ($body !== null)
        {
            /** @var Nodes\RegularBlock $body */
            $body = NodeConverter::convert($body, Nodes\RegularBlock::class, $this->phpVersion);
            $body->detach();
            $body->parent = $this;
        }
        if ($this->body !== null)
        {
            $this->body->detach();
        }
        $this->body = $body;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, 'keyword');
            if ($this->name === null) throw ValidationException::childRequired($this, 'name');
            if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, 'leftParenthesis');
            if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, 'rightParenthesis');
            if ($this->body === null) throw ValidationException::childRequired($this, 'body');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->parameters->_validate($flags);
        if ($this->returnType)
        {
            $this->returnType->_validate($flags);
        }
        $this->body->_validate($flags);
    }
}
