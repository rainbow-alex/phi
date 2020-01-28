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

abstract class GeneratedTernaryExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $test;

    /**
     * @var Token|null
     */
    private $questionMark;

    /**
     * @var Nodes\Expression|null
     */
    private $then;

    /**
     * @var Token|null
     */
    private $colon;

    /**
     * @var Nodes\Expression|null
     */
    private $else;


    /**
     * @param Nodes\Expression|Node|string|null $test
     * @param Nodes\Expression|Node|string|null $then
     * @param Nodes\Expression|Node|string|null $else
     */
    public function __construct($test = null, $then = null, $else = null)
    {
        if ($test !== null)
        {
            $this->setTest($test);
        }
        if ($then !== null)
        {
            $this->setThen($then);
        }
        if ($else !== null)
        {
            $this->setElse($else);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression $test
     * @param Token $questionMark
     * @param Nodes\Expression|null $then
     * @param Token $colon
     * @param Nodes\Expression $else
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $test, $questionMark, $then, $colon, $else)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->test = $test;
        $test->parent = $instance;
        $instance->questionMark = $questionMark;
        $questionMark->parent = $instance;
        $instance->then = $then;
        if ($then) $then->parent = $instance;
        $instance->colon = $colon;
        $colon->parent = $instance;
        $instance->else = $else;
        $else->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "test" => &$this->test,
            "questionMark" => &$this->questionMark,
            "then" => &$this->then,
            "colon" => &$this->colon,
            "else" => &$this->else,
        ];
        return $refs;
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

    public function getQuestionMark(): Token
    {
        if ($this->questionMark === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->questionMark;
    }

    public function hasQuestionMark(): bool
    {
        return $this->questionMark !== null;
    }

    /**
     * @param Token|Node|string|null $questionMark
     */
    public function setQuestionMark($questionMark): void
    {
        if ($questionMark !== null)
        {
            /** @var Token $questionMark */
            $questionMark = NodeConverter::convert($questionMark, Token::class, $this->phpVersion);
            $questionMark->detach();
            $questionMark->parent = $this;
        }
        if ($this->questionMark !== null)
        {
            $this->questionMark->detach();
        }
        $this->questionMark = $questionMark;
    }

    public function getThen(): ?Nodes\Expression
    {
        return $this->then;
    }

    public function hasThen(): bool
    {
        return $this->then !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $then
     */
    public function setThen($then): void
    {
        if ($then !== null)
        {
            /** @var Nodes\Expression $then */
            $then = NodeConverter::convert($then, Nodes\Expression::class, $this->phpVersion);
            $then->detach();
            $then->parent = $this;
        }
        if ($this->then !== null)
        {
            $this->then->detach();
        }
        $this->then = $then;
    }

    public function getColon(): Token
    {
        if ($this->colon === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->colon;
    }

    public function hasColon(): bool
    {
        return $this->colon !== null;
    }

    /**
     * @param Token|Node|string|null $colon
     */
    public function setColon($colon): void
    {
        if ($colon !== null)
        {
            /** @var Token $colon */
            $colon = NodeConverter::convert($colon, Token::class, $this->phpVersion);
            $colon->detach();
            $colon->parent = $this;
        }
        if ($this->colon !== null)
        {
            $this->colon->detach();
        }
        $this->colon = $colon;
    }

    public function getElse(): Nodes\Expression
    {
        if ($this->else === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->else;
    }

    public function hasElse(): bool
    {
        return $this->else !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $else
     */
    public function setElse($else): void
    {
        if ($else !== null)
        {
            /** @var Nodes\Expression $else */
            $else = NodeConverter::convert($else, Nodes\Expression::class, $this->phpVersion);
            $else->detach();
            $else->parent = $this;
        }
        if ($this->else !== null)
        {
            $this->else->detach();
        }
        $this->else = $else;
    }

    protected function _validate(int $flags): void
    {
        if ($this->test === null) throw ValidationException::childRequired($this, "test");
        if ($this->questionMark === null) throw ValidationException::childRequired($this, "questionMark");
        if ($this->colon === null) throw ValidationException::childRequired($this, "colon");
        if ($this->else === null) throw ValidationException::childRequired($this, "else");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->test->_validate($flags);
        if ($this->then)
        {
            $this->then->_validate($flags);
        }
        $this->else->_validate($flags);
    }
}
