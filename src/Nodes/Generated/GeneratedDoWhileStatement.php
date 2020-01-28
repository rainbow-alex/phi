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

abstract class GeneratedDoWhileStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword1;

    /**
     * @var Nodes\Block|null
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
    private $delimiter;


    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword1
     * @param Nodes\Block $block
     * @param Token $keyword2
     * @param Token $leftParenthesis
     * @param Nodes\Expression $test
     * @param Token $rightParenthesis
     * @param Token $delimiter
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword1, $block, $keyword2, $leftParenthesis, $test, $rightParenthesis, $delimiter)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword1 = $keyword1;
        $keyword1->parent = $instance;
        $instance->block = $block;
        $block->parent = $instance;
        $instance->keyword2 = $keyword2;
        $keyword2->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->test = $test;
        $test->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword1" => &$this->keyword1,
            "block" => &$this->block,
            "keyword2" => &$this->keyword2,
            "leftParenthesis" => &$this->leftParenthesis,
            "test" => &$this->test,
            "rightParenthesis" => &$this->rightParenthesis,
            "delimiter" => &$this->delimiter,
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
            $keyword1 = NodeConverter::convert($keyword1, Token::class, $this->phpVersion);
            $keyword1->detach();
            $keyword1->parent = $this;
        }
        if ($this->keyword1 !== null)
        {
            $this->keyword1->detach();
        }
        $this->keyword1 = $keyword1;
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
            $keyword2 = NodeConverter::convert($keyword2, Token::class, $this->phpVersion);
            $keyword2->detach();
            $keyword2->parent = $this;
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
            $test = NodeConverter::convert($test, Nodes\Expression::class, $this->phpVersion);
            $test->detach();
            $test->parent = $this;
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

    public function getDelimiter(): Token
    {
        if ($this->delimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->delimiter;
    }

    public function hasDelimiter(): bool
    {
        return $this->delimiter !== null;
    }

    /**
     * @param Token|Node|string|null $delimiter
     */
    public function setDelimiter($delimiter): void
    {
        if ($delimiter !== null)
        {
            /** @var Token $delimiter */
            $delimiter = NodeConverter::convert($delimiter, Token::class, $this->phpVersion);
            $delimiter->detach();
            $delimiter->parent = $this;
        }
        if ($this->delimiter !== null)
        {
            $this->delimiter->detach();
        }
        $this->delimiter = $delimiter;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword1 === null) throw ValidationException::childRequired($this, "keyword1");
        if ($this->block === null) throw ValidationException::childRequired($this, "block");
        if ($this->keyword2 === null) throw ValidationException::childRequired($this, "keyword2");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->test === null) throw ValidationException::childRequired($this, "test");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($this->delimiter === null) throw ValidationException::childRequired($this, "delimiter");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->block->_validate($flags);
        $this->test->_validate($flags);
    }
}
