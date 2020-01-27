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

abstract class GeneratedNewExpression extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Nodes\Expression|null
     */
    private $class;

    /**
     * @var Token|null
     */
    private $leftParenthesis;

    /**
     * @var SeparatedNodesList|Nodes\Argument[]
     */
    private $arguments;

    /**
     * @var Token|null
     */
    private $rightParenthesis;

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function __construct($class = null)
    {
        if ($class !== null)
        {
            $this->setClass($class);
        }
        $this->arguments = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword
     * @param Nodes\Expression|null $class
     * @param Token|null $leftParenthesis
     * @param mixed[] $arguments
     * @param Token|null $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $class, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $instance->keyword->parent = $instance;
        $instance->class = $class;
        $instance->class->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        if ($leftParenthesis)
        {
            $instance->leftParenthesis->parent = $instance;
        }
        $instance->arguments->__initUnchecked($arguments);
        $instance->arguments->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        if ($rightParenthesis)
        {
            $instance->rightParenthesis->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "class" => &$this->class,
            "leftParenthesis" => &$this->leftParenthesis,
            "arguments" => &$this->arguments,
            "rightParenthesis" => &$this->rightParenthesis,
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

    public function getClass(): Nodes\Expression
    {
        if ($this->class === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->class;
    }

    public function hasClass(): bool
    {
        return $this->class !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $class
     */
    public function setClass($class): void
    {
        if ($class !== null)
        {
            /** @var Nodes\Expression $class */
            $class = NodeConverter::convert($class, Nodes\Expression::class, $this->phpVersion);
            $class->detach();
            $class->parent = $this;
        }
        if ($this->class !== null)
        {
            $this->class->detach();
        }
        $this->class = $class;
    }

    public function getLeftParenthesis(): ?Token
    {
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
     * @return SeparatedNodesList|Nodes\Argument[]
     */
    public function getArguments(): SeparatedNodesList
    {
        return $this->arguments;
    }

    /**
     * @param Nodes\Argument $argument
     */
    public function addArgument($argument): void
    {
        /** @var Nodes\Argument $argument */
        $argument = NodeConverter::convert($argument, Nodes\Argument::class, $this->phpVersion);
        $this->arguments->add($argument);
    }

    public function getRightParenthesis(): ?Token
    {
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

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
            if ($this->class === null) throw ValidationException::childRequired($this, "class");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->class->_validate($flags);
        $this->arguments->_validate($flags);
    }
}
