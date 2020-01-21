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

abstract class GeneratedBlock extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'leftBrace' => new IsToken('{'),
                'statements' => new EachItem(new IsInstanceOf(Nodes\Statement::class)),
                'rightBrace' => new IsToken('}'),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $leftBrace;
    /**
     * @var NodesList|Nodes\Statement[]
     */
    private $statements;
    /**
     * @var Token|null
     */
    private $rightBrace;

    /**
     * @param Nodes\Statement $statement
     */
    public function __construct($statement = null)
    {
        parent::__construct();
        $this->statements = new NodesList();
        if ($statement !== null)
        {
            $this->addStatement($statement);
        }
    }

    /**
     * @param Token|null $leftBrace
     * @param mixed[] $statements
     * @param Token|null $rightBrace
     * @return static
     */
    public static function __instantiateUnchecked($leftBrace, $statements, $rightBrace)
    {
        $instance = new static();
        $instance->leftBrace = $leftBrace;
        $instance->statements->__initUnchecked($statements);
        $instance->rightBrace = $rightBrace;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'leftBrace' => &$this->leftBrace,
            'statements' => &$this->statements,
            'rightBrace' => &$this->rightBrace,
        ];
        return $refs;
    }

    public function getLeftBrace(): Token
    {
        if ($this->leftBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param Token|Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var Token $leftBrace */
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->_phpVersion);
            $leftBrace->_attachTo($this);
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
    }

    /**
     * @return NodesList|Nodes\Statement[]
     */
    public function getStatements(): NodesList
    {
        return $this->statements;
    }

    /**
     * @param Nodes\Statement $statement
     */
    public function addStatement($statement): void
    {
        /** @var Nodes\Statement $statement */
        $statement = NodeConverter::convert($statement, Nodes\Statement::class);
        $this->statements->add($statement);
    }

    public function getRightBrace(): Token
    {
        if ($this->rightBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param Token|Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var Token $rightBrace */
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->_phpVersion);
            $rightBrace->_attachTo($this);
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }
}
