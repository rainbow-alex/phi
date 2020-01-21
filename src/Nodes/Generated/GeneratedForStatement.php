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

abstract class GeneratedForStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_FOR),
                'leftParenthesis' => new IsToken('('),
                'init' => new Optional(new Any),
                'separator1' => new IsToken(';'),
                'test' => new Optional(new Any),
                'separator2' => new IsToken(';'),
                'step' => new Optional(new Any),
                'rightParenthesis' => new IsToken(')'),
                'block' => new Any,
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
    private $leftParenthesis;
    /**
     * @var Nodes\Expression|null
     */
    private $init;
    /**
     * @var Token|null
     */
    private $separator1;
    /**
     * @var Nodes\Expression|null
     */
    private $test;
    /**
     * @var Token|null
     */
    private $separator2;
    /**
     * @var Nodes\Expression|null
     */
    private $step;
    /**
     * @var Token|null
     */
    private $rightParenthesis;
    /**
     * @var Nodes\Statement|null
     */
    private $block;

    /**
     * @param Nodes\Expression|Node|string|null $init
     * @param Nodes\Expression|Node|string|null $test
     * @param Nodes\Expression|Node|string|null $step
     * @param Nodes\Statement|Node|string|null $block
     */
    public function __construct($init = null, $test = null, $step = null, $block = null)
    {
        parent::__construct();
        if ($init !== null)
        {
            $this->setInit($init);
        }
        if ($test !== null)
        {
            $this->setTest($test);
        }
        if ($step !== null)
        {
            $this->setStep($step);
        }
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $leftParenthesis
     * @param Nodes\Expression|null $init
     * @param Token|null $separator1
     * @param Nodes\Expression|null $test
     * @param Token|null $separator2
     * @param Nodes\Expression|null $step
     * @param Token|null $rightParenthesis
     * @param Nodes\Statement|null $block
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $init, $separator1, $test, $separator2, $step, $rightParenthesis, $block)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->init = $init;
        $instance->separator1 = $separator1;
        $instance->test = $test;
        $instance->separator2 = $separator2;
        $instance->step = $step;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->block = $block;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'leftParenthesis' => &$this->leftParenthesis,
            'init' => &$this->init,
            'separator1' => &$this->separator1,
            'test' => &$this->test,
            'separator2' => &$this->separator2,
            'step' => &$this->step,
            'rightParenthesis' => &$this->rightParenthesis,
            'block' => &$this->block,
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

    public function getInit(): ?Nodes\Expression
    {
        return $this->init;
    }

    public function hasInit(): bool
    {
        return $this->init !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $init
     */
    public function setInit($init): void
    {
        if ($init !== null)
        {
            /** @var Nodes\Expression $init */
            $init = NodeConverter::convert($init, Nodes\Expression::class, $this->_phpVersion);
            $init->_attachTo($this);
        }
        if ($this->init !== null)
        {
            $this->init->detach();
        }
        $this->init = $init;
    }

    public function getSeparator1(): Token
    {
        if ($this->separator1 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->separator1;
    }

    public function hasSeparator1(): bool
    {
        return $this->separator1 !== null;
    }

    /**
     * @param Token|Node|string|null $separator1
     */
    public function setSeparator1($separator1): void
    {
        if ($separator1 !== null)
        {
            /** @var Token $separator1 */
            $separator1 = NodeConverter::convert($separator1, Token::class, $this->_phpVersion);
            $separator1->_attachTo($this);
        }
        if ($this->separator1 !== null)
        {
            $this->separator1->detach();
        }
        $this->separator1 = $separator1;
    }

    public function getTest(): ?Nodes\Expression
    {
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

    public function getSeparator2(): Token
    {
        if ($this->separator2 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->separator2;
    }

    public function hasSeparator2(): bool
    {
        return $this->separator2 !== null;
    }

    /**
     * @param Token|Node|string|null $separator2
     */
    public function setSeparator2($separator2): void
    {
        if ($separator2 !== null)
        {
            /** @var Token $separator2 */
            $separator2 = NodeConverter::convert($separator2, Token::class, $this->_phpVersion);
            $separator2->_attachTo($this);
        }
        if ($this->separator2 !== null)
        {
            $this->separator2->detach();
        }
        $this->separator2 = $separator2;
    }

    public function getStep(): ?Nodes\Expression
    {
        return $this->step;
    }

    public function hasStep(): bool
    {
        return $this->step !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $step
     */
    public function setStep($step): void
    {
        if ($step !== null)
        {
            /** @var Nodes\Expression $step */
            $step = NodeConverter::convert($step, Nodes\Expression::class, $this->_phpVersion);
            $step->_attachTo($this);
        }
        if ($this->step !== null)
        {
            $this->step->detach();
        }
        $this->step = $step;
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
}
