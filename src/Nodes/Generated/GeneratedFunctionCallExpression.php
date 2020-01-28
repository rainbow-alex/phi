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

abstract class GeneratedFunctionCallExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $callee;

    /**
     * @var Token|null
     */
    private $leftParenthesis;

    /**
     * @var SeparatedNodesList|Nodes\Argument[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Argument>
     */
    private $arguments;

    /**
     * @var Token|null
     */
    private $rightParenthesis;


    /**
     * @param Nodes\Expression|Node|string|null $callee
     */
    public function __construct($callee = null)
    {
        if ($callee !== null)
        {
            $this->setCallee($callee);
        }
        $this->arguments = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression $callee
     * @param Token $leftParenthesis
     * @param mixed[] $arguments
     * @param Token $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $callee, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->callee = $callee;
        $callee->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->arguments->__initUnchecked($arguments);
        $instance->arguments->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "callee" => &$this->callee,
            "leftParenthesis" => &$this->leftParenthesis,
            "arguments" => &$this->arguments,
            "rightParenthesis" => &$this->rightParenthesis,
        ];
        return $refs;
    }

    public function getCallee(): Nodes\Expression
    {
        if ($this->callee === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->callee;
    }

    public function hasCallee(): bool
    {
        return $this->callee !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $callee
     */
    public function setCallee($callee): void
    {
        if ($callee !== null)
        {
            /** @var Nodes\Expression $callee */
            $callee = NodeConverter::convert($callee, Nodes\Expression::class, $this->phpVersion);
            $callee->detach();
            $callee->parent = $this;
        }
        if ($this->callee !== null)
        {
            $this->callee->detach();
        }
        $this->callee = $callee;
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
     * @return SeparatedNodesList|Nodes\Argument[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Argument>
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

    protected function _validate(int $flags): void
    {
        if ($this->callee === null) throw ValidationException::childRequired($this, "callee");
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
        $this->callee->_validate($flags);
        $this->arguments->_validate($flags);
    }
}
