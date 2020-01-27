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
    private $semiColon;

    /**
     */
    public function __construct()
    {
    }

    /**
     * @param int $phpVersion
     * @param Token|null $keyword1
     * @param Nodes\Block|null $block
     * @param Token|null $keyword2
     * @param Token|null $leftParenthesis
     * @param Nodes\Expression|null $test
     * @param Token|null $rightParenthesis
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword1, $block, $keyword2, $leftParenthesis, $test, $rightParenthesis, $semiColon)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword1 = $keyword1;
        $instance->keyword1->parent = $instance;
        $instance->block = $block;
        $instance->block->parent = $instance;
        $instance->keyword2 = $keyword2;
        $instance->keyword2->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $instance->leftParenthesis->parent = $instance;
        $instance->test = $test;
        $instance->test->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $instance->rightParenthesis->parent = $instance;
        $instance->semiColon = $semiColon;
        if ($semiColon)
        {
            $instance->semiColon->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->keyword1 === null) throw ValidationException::childRequired($this, 'keyword1');
            if ($this->block === null) throw ValidationException::childRequired($this, 'block');
            if ($this->keyword2 === null) throw ValidationException::childRequired($this, 'keyword2');
            if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, 'leftParenthesis');
            if ($this->test === null) throw ValidationException::childRequired($this, 'test');
            if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, 'rightParenthesis');
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
