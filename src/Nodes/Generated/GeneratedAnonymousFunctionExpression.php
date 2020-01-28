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

abstract class GeneratedAnonymousFunctionExpression extends Nodes\Expression
{
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
    private $byReference;

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
     * @var Nodes\AnonymousFunctionUse|null
     */
    private $use;

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
     * @param Token|null $static
     * @param Token $keyword
     * @param Token|null $byReference
     * @param Token $leftParenthesis
     * @param mixed[] $parameters
     * @param Token $rightParenthesis
     * @param Nodes\AnonymousFunctionUse|null $use
     * @param Nodes\ReturnType|null $returnType
     * @param Nodes\RegularBlock $body
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $static, $keyword, $byReference, $leftParenthesis, $parameters, $rightParenthesis, $use, $returnType, $body)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->static = $static;
        if ($static) $static->parent = $instance;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->byReference = $byReference;
        if ($byReference) $byReference->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->parameters->__initUnchecked($parameters);
        $instance->parameters->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->use = $use;
        if ($use) $use->parent = $instance;
        $instance->returnType = $returnType;
        if ($returnType) $returnType->parent = $instance;
        $instance->body = $body;
        $body->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "static" => &$this->static,
            "keyword" => &$this->keyword,
            "byReference" => &$this->byReference,
            "leftParenthesis" => &$this->leftParenthesis,
            "parameters" => &$this->parameters,
            "rightParenthesis" => &$this->rightParenthesis,
            "use" => &$this->use,
            "returnType" => &$this->returnType,
            "body" => &$this->body,
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
            $static = NodeConverter::convert($static, Token::class, $this->phpVersion);
            $static->detach();
            $static->parent = $this;
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
            $use = NodeConverter::convert($use, Nodes\AnonymousFunctionUse::class, $this->phpVersion);
            $use->detach();
            $use->parent = $this;
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($this->body === null) throw ValidationException::childRequired($this, "body");
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
        if ($this->use)
        {
            $this->use->_validate($flags);
        }
        if ($this->returnType)
        {
            $this->returnType->_validate($flags);
        }
        $this->body->_validate($flags);
    }
}
