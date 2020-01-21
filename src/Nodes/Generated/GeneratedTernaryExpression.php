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

abstract class GeneratedTernaryExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'test' => new Specs\IsReadExpression,
                'questionMark' => new IsToken('?'),
                'then' => new Optional(new Specs\IsReadExpression),
                'colon' => new IsToken(':'),
                'else' => new Specs\IsReadExpression,
            ]),
        ];
    }

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
        parent::__construct();
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
     * @param Nodes\Expression|null $test
     * @param Token|null $questionMark
     * @param Nodes\Expression|null $then
     * @param Token|null $colon
     * @param Nodes\Expression|null $else
     * @return static
     */
    public static function __instantiateUnchecked($test, $questionMark, $then, $colon, $else)
    {
        $instance = new static();
        $instance->test = $test;
        $instance->questionMark = $questionMark;
        $instance->then = $then;
        $instance->colon = $colon;
        $instance->else = $else;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'test' => &$this->test,
            'questionMark' => &$this->questionMark,
            'then' => &$this->then,
            'colon' => &$this->colon,
            'else' => &$this->else,
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
            $test = NodeConverter::convert($test, Nodes\Expression::class, $this->_phpVersion);
            $test->_attachTo($this);
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
            $questionMark = NodeConverter::convert($questionMark, Token::class, $this->_phpVersion);
            $questionMark->_attachTo($this);
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
            $then = NodeConverter::convert($then, Nodes\Expression::class, $this->_phpVersion);
            $then->_attachTo($this);
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
            $colon = NodeConverter::convert($colon, Token::class, $this->_phpVersion);
            $colon->_attachTo($this);
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
            $else = NodeConverter::convert($else, Nodes\Expression::class, $this->_phpVersion);
            $else->_attachTo($this);
        }
        if ($this->else !== null)
        {
            $this->else->detach();
        }
        $this->else = $else;
    }
}
