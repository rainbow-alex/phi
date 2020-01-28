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

abstract class GeneratedMethod extends Nodes\ClassLikeMember
{
    /**
     * @var NodesList|Token[]
     * @phpstan-var NodesList<\Phi\Token>
     */
    private $modifiers;

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
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Parameter>
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
     * @var Token|null
     */
    private $semiColon;


    /**
     * @param Token|Node|string|null $name
     */
    public function __construct($name = null)
    {
        $this->modifiers = new NodesList();
        if ($name !== null)
        {
            $this->setName($name);
        }
        $this->parameters = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param mixed[] $modifiers
     * @param Token $keyword
     * @param Token|null $byReference
     * @param Token $name
     * @param Token $leftParenthesis
     * @param mixed[] $parameters
     * @param Token $rightParenthesis
     * @param Nodes\ReturnType|null $returnType
     * @param Nodes\RegularBlock|null $body
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $modifiers, $keyword, $byReference, $name, $leftParenthesis, $parameters, $rightParenthesis, $returnType, $body, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->modifiers->__initUnchecked($modifiers);
        $instance->modifiers->parent = $instance;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->parameters->__initUnchecked($parameters);
        $instance->parameters->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->returnType = $returnType;
        if ($returnType) $returnType->parent = $instance;
        $instance->body = $body;
        if ($body) $body->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon) $semiColon->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "modifiers" => &$this->modifiers,
            "keyword" => &$this->keyword,
            "byReference" => &$this->byReference,
            "name" => &$this->name,
            "leftParenthesis" => &$this->leftParenthesis,
            "parameters" => &$this->parameters,
            "rightParenthesis" => &$this->rightParenthesis,
            "returnType" => &$this->returnType,
            "body" => &$this->body,
            "semiColon" => &$this->semiColon,
        ];
        return $refs;
    }

    /**
     * @return NodesList|Token[]
     * @phpstan-return NodesList<\Phi\Token>
     */
    public function getModifiers(): NodesList
    {
        return $this->modifiers;
    }

    /**
     * @param Token $modifier
     */
    public function addModifier($modifier): void
    {
        /** @var Token $modifier */
        $modifier = NodeConverter::convert($modifier, Token::class, $this->phpVersion);
        $this->modifiers->add($modifier);
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
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Parameter>
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

    public function getBody(): ?Nodes\RegularBlock
    {
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

    public function getSemiColon(): ?Token
    {
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->name === null) throw ValidationException::childRequired($this, "name");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($flags & self::VALIDATE_TYPES)
        {
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
        if ($this->body)
        {
            $this->body->_validate($flags);
        }
    }
}
