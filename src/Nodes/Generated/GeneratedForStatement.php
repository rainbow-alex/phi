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

abstract class GeneratedForStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $leftParenthesis;

    /**
     * @var SeparatedNodesList|Nodes\Expression[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Expression>
     */
    private $inits;

    /**
     * @var Token|null
     */
    private $separator1;

    /**
     * @var SeparatedNodesList|Nodes\Expression[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Expression>
     */
    private $tests;

    /**
     * @var Token|null
     */
    private $separator2;

    /**
     * @var SeparatedNodesList|Nodes\Expression[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\Expression>
     */
    private $steps;

    /**
     * @var Token|null
     */
    private $rightParenthesis;

    /**
     * @var Nodes\Block|null
     */
    private $block;


    /**
     * @param Nodes\Expression $init
     * @param Nodes\Expression $test
     * @param Nodes\Expression $step
     * @param Nodes\Block|Node|string|null $block
     */
    public function __construct($init = null, $test = null, $step = null, $block = null)
    {
        $this->inits = new SeparatedNodesList();
        if ($init !== null)
        {
            $this->addInit($init);
        }
        $this->tests = new SeparatedNodesList();
        if ($test !== null)
        {
            $this->addTest($test);
        }
        $this->steps = new SeparatedNodesList();
        if ($step !== null)
        {
            $this->addStep($step);
        }
        if ($block !== null)
        {
            $this->setBlock($block);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $leftParenthesis
     * @param mixed[] $inits
     * @param Token $separator1
     * @param mixed[] $tests
     * @param Token $separator2
     * @param mixed[] $steps
     * @param Token $rightParenthesis
     * @param Nodes\Block $block
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $leftParenthesis, $inits, $separator1, $tests, $separator2, $steps, $rightParenthesis, $block)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->inits->__initUnchecked($inits);
        $instance->inits->parent = $instance;
        $instance->separator1 = $separator1;
        $separator1->parent = $instance;
        $instance->tests->__initUnchecked($tests);
        $instance->tests->parent = $instance;
        $instance->separator2 = $separator2;
        $separator2->parent = $instance;
        $instance->steps->__initUnchecked($steps);
        $instance->steps->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "leftParenthesis" => &$this->leftParenthesis,
            "inits" => &$this->inits,
            "separator1" => &$this->separator1,
            "tests" => &$this->tests,
            "separator2" => &$this->separator2,
            "steps" => &$this->steps,
            "rightParenthesis" => &$this->rightParenthesis,
            "block" => &$this->block,
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
     * @return SeparatedNodesList|Nodes\Expression[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Expression>
     */
    public function getInits(): SeparatedNodesList
    {
        return $this->inits;
    }

    /**
     * @param Nodes\Expression $init
     */
    public function addInit($init): void
    {
        /** @var Nodes\Expression $init */
        $init = NodeConverter::convert($init, Nodes\Expression::class, $this->phpVersion);
        $this->inits->add($init);
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
            $separator1 = NodeConverter::convert($separator1, Token::class, $this->phpVersion);
            $separator1->detach();
            $separator1->parent = $this;
        }
        if ($this->separator1 !== null)
        {
            $this->separator1->detach();
        }
        $this->separator1 = $separator1;
    }

    /**
     * @return SeparatedNodesList|Nodes\Expression[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Expression>
     */
    public function getTests(): SeparatedNodesList
    {
        return $this->tests;
    }

    /**
     * @param Nodes\Expression $test
     */
    public function addTest($test): void
    {
        /** @var Nodes\Expression $test */
        $test = NodeConverter::convert($test, Nodes\Expression::class, $this->phpVersion);
        $this->tests->add($test);
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
            $separator2 = NodeConverter::convert($separator2, Token::class, $this->phpVersion);
            $separator2->detach();
            $separator2->parent = $this;
        }
        if ($this->separator2 !== null)
        {
            $this->separator2->detach();
        }
        $this->separator2 = $separator2;
    }

    /**
     * @return SeparatedNodesList|Nodes\Expression[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\Expression>
     */
    public function getSteps(): SeparatedNodesList
    {
        return $this->steps;
    }

    /**
     * @param Nodes\Expression $step
     */
    public function addStep($step): void
    {
        /** @var Nodes\Expression $step */
        $step = NodeConverter::convert($step, Nodes\Expression::class, $this->phpVersion);
        $this->steps->add($step);
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

    public function getBlock(): Nodes\Block
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
     * @param Nodes\Block|Node|string|null $block
     */
    public function setBlock($block): void
    {
        if ($block !== null)
        {
            /** @var Nodes\Block $block */
            $block = NodeConverter::convert($block, Nodes\Block::class, $this->phpVersion);
            $block->detach();
            $block->parent = $this;
        }
        if ($this->block !== null)
        {
            $this->block->detach();
        }
        $this->block = $block;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->separator1 === null) throw ValidationException::childRequired($this, "separator1");
        if ($this->separator2 === null) throw ValidationException::childRequired($this, "separator2");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($this->block === null) throw ValidationException::childRequired($this, "block");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->inits->_validate($flags);
        $this->tests->_validate($flags);
        $this->steps->_validate($flags);
        $this->block->_validate($flags);
    }
}
