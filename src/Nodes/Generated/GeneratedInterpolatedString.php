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

abstract class GeneratedInterpolatedString extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'leftDelimiter' => new IsToken('"', \T_START_HEREDOC),
                'parts' => new EachItem(new IsInstanceOf(Nodes\InterpolatedStringPart::class)),
                'rightDelimiter' => new IsToken('"', \T_END_HEREDOC),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $leftDelimiter;
    /**
     * @var NodesList|Nodes\InterpolatedStringPart[]
     */
    private $parts;
    /**
     * @var Token|null
     */
    private $rightDelimiter;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->parts = new NodesList();
    }

    /**
     * @param Token|null $leftDelimiter
     * @param mixed[] $parts
     * @param Token|null $rightDelimiter
     * @return static
     */
    public static function __instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter)
    {
        $instance = new static();
        $instance->leftDelimiter = $leftDelimiter;
        $instance->parts->__initUnchecked($parts);
        $instance->rightDelimiter = $rightDelimiter;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'leftDelimiter' => &$this->leftDelimiter,
            'parts' => &$this->parts,
            'rightDelimiter' => &$this->rightDelimiter,
        ];
        return $refs;
    }

    public function getLeftDelimiter(): Token
    {
        if ($this->leftDelimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftDelimiter;
    }

    public function hasLeftDelimiter(): bool
    {
        return $this->leftDelimiter !== null;
    }

    /**
     * @param Token|Node|string|null $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter): void
    {
        if ($leftDelimiter !== null)
        {
            /** @var Token $leftDelimiter */
            $leftDelimiter = NodeConverter::convert($leftDelimiter, Token::class, $this->_phpVersion);
            $leftDelimiter->_attachTo($this);
        }
        if ($this->leftDelimiter !== null)
        {
            $this->leftDelimiter->detach();
        }
        $this->leftDelimiter = $leftDelimiter;
    }

    /**
     * @return NodesList|Nodes\InterpolatedStringPart[]
     */
    public function getParts(): NodesList
    {
        return $this->parts;
    }

    /**
     * @param Nodes\InterpolatedStringPart $part
     */
    public function addPart($part): void
    {
        /** @var Nodes\InterpolatedStringPart $part */
        $part = NodeConverter::convert($part, Nodes\InterpolatedStringPart::class);
        $this->parts->add($part);
    }

    public function getRightDelimiter(): Token
    {
        if ($this->rightDelimiter === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightDelimiter;
    }

    public function hasRightDelimiter(): bool
    {
        return $this->rightDelimiter !== null;
    }

    /**
     * @param Token|Node|string|null $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter): void
    {
        if ($rightDelimiter !== null)
        {
            /** @var Token $rightDelimiter */
            $rightDelimiter = NodeConverter::convert($rightDelimiter, Token::class, $this->_phpVersion);
            $rightDelimiter->_attachTo($this);
        }
        if ($this->rightDelimiter !== null)
        {
            $this->rightDelimiter->detach();
        }
        $this->rightDelimiter = $rightDelimiter;
    }
}
