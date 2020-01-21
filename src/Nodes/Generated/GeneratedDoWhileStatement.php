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

abstract class GeneratedDoWhileStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword1' => new IsToken(\T_DO),
                'block' => new Any,
                'keyword2' => new IsToken(\T_WHILE),
                'leftParenthesis' => new IsToken('('),
                'test' => new Specs\IsReadExpression,
                'rightParenthesis' => new IsToken(')'),
                'semiColon' => new Optional(new IsToken(';')),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword1;
    /**
     * @var Nodes\Statement|null
     */
    private $block;
    /**
     * @var Token|null
     */
    private $keyword2;
    /**
     * @var Token|null
     */
    private $leftParenthesis;
    /**
     * @var Nodes\Expression|null
     */
    private $test;
    /**
     * @var Token|null
     */
    private $rightParenthesis;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Token|null $keyword1
     * @param Nodes\Statement|null $block
     * @param Token|null $keyword2
     * @param Token|null $leftParenthesis
     * @param Nodes\Expression|null $test
     * @param Token|null $rightParenthesis
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($keyword1, $block, $keyword2, $leftParenthesis, $test, $rightParenthesis, $semiColon)
    {
        $instance = new static();
        $instance->keyword1 = $keyword1;
        $instance->block = $block;
        $instance->keyword2 = $keyword2;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->test = $test;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword1' => &$this->keyword1,
            'block' => &$this->block,
            'keyword2' => &$this->keyword2,
            'leftParenthesis' => &$this->leftParenthesis,
            'test' => &$this->test,
            'rightParenthesis' => &$this->rightParenthesis,
            'semiColon' => &$this->semiColon,
        ];
        return $refs;
    }

    public function getKeyword1(): Token
    {
        if ($this->keyword1 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword1;
    }

    public function hasKeyword1(): bool
    {
        return $this->keyword1 !== null;
    }

    /**
     * @param Token|Node|string|null $keyword1
     */
    public function setKeyword1($keyword1): void
    {
        if ($keyword1 !== null)
        {
            /** @var Token $keyword1 */
            $keyword1 = NodeConverter::convert($keyword1, Token::class, $this->_phpVersion);
            $keyword1->_attachTo($this);
        }
        if ($this->keyword1 !== null)
        {
            $this->keyword1->detach();
        }
        $this->keyword1 = $keyword1;
    }

    public function getBlock(): Nodes\Statement
    {
        if ($this->block === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block !== null;
    }

    /**
     * @param Nodes\Statement|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Statement $block */
            $block = NodeConverter::convert($block, Nodes\Statement::class, $this->_phpVersion);
            $block->_attachTo($this);
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    public function getKeyword2(): Token
    {
        if ($this->keyword2 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword2;
    }

    public function hasKeyword2(): bool
    {
        return $this->keyword2 !== null;
    }

    /**
     * @param Token|Node|string|null $keyword2
     */
    public function setKeyword2($keyword2): void
    {
        if ($keyword2 !== null)
        {
            /** @var Token $keyword2 */
            $keyword2 = NodeConverter::convert($keyword2, Token::class, $this->_phpVersion);
            $keyword2->_attachTo($this);
        }
        if ($this->keyword2 !== null)
        {
            $this->keyword2->detach();
        }
        $this->keyword2 = $keyword2;
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

    public function getTest(): Nodes\Expression
    {
        if ($this->test === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->test;
    }

    public function hasTest(): bool
    {
        return $this->test !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $test
     */
    public function setTest($test): void
    {
        if ($test !== null)
        {
            /** @var Nodes\Expression $test */
            $test = NodeConverter::convert($test, Nodes\Expression::class, $this->_phpVersion);
            $test->_attachTo($this);
        }
        if ($this->test !== null)
        {
            $this->test->detach();
        }
        $this->test = $test;
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
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->_phpVersion);
            $semiColon->_attachTo($this);
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }
}
