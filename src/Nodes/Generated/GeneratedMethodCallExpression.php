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

abstract class GeneratedMethodCallExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $object;

    /**
     * @var Token|null
     */
    private $operator;

    /**
     * @var Nodes\MemberName|null
     */
    private $name;

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
     * @param Nodes\Expression|Node|string|null $object
     * @param Token|Node|string|null $operator
     */
    public function __construct($object = null, $operator = null)
    {
        if ($object !== null)
        {
            $this->setObject($object);
        }
        if ($operator !== null)
        {
            $this->setOperator($operator);
        }
        $this->arguments = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression|null $object
     * @param Token|null $operator
     * @param Nodes\MemberName|null $name
     * @param Token|null $leftParenthesis
     * @param mixed[] $arguments
     * @param Token|null $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $object, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->object = $object;
        $instance->object->parent = $instance;
        $instance->operator = $operator;
        $instance->operator->parent = $instance;
        $instance->name = $name;
        $instance->name->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->leftParenthesis->parent = $instance;
        $instance->arguments->__initUnchecked($arguments);
        $instance->arguments->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->rightParenthesis->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "object" => &$this->object,
            "operator" => &$this->operator,
            "name" => &$this->name,
            "leftParenthesis" => &$this->leftParenthesis,
            "arguments" => &$this->arguments,
            "rightParenthesis" => &$this->rightParenthesis,
        ];
        return $refs;
    }

    public function getObject(): Nodes\Expression
    {
        if ($this->object === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->object;
    }

    public function hasObject(): bool
    {
        return $this->object !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $object
     */
    public function setObject($object): void
    {
        if ($object !== null)
        {
            /** @var Nodes\Expression $object */
            $object = NodeConverter::convert($object, Nodes\Expression::class, $this->phpVersion);
            $object->detach();
            $object->parent = $this;
        }
        if ($this->object !== null)
        {
            $this->object->detach();
        }
        $this->object = $object;
    }

    public function getOperator(): Token
    {
        if ($this->operator === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param Token|Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var Token $operator */
            $operator = NodeConverter::convert($operator, Token::class, $this->phpVersion);
            $operator->detach();
            $operator->parent = $this;
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
    }

    public function getName(): Nodes\MemberName
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
     * @param Nodes\MemberName|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Nodes\MemberName $name */
            $name = NodeConverter::convert($name, Nodes\MemberName::class, $this->phpVersion);
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
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->object === null) throw ValidationException::childRequired($this, "object");
            if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
            if ($this->name === null) throw ValidationException::childRequired($this, "name");
            if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
            if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->object->_validate($flags);
        $this->name->_validate($flags);
        $this->arguments->_validate($flags);
    }
}
