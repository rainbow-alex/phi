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

abstract class GeneratedConstantStringLiteral extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'source' => new IsToken(\T_CONSTANT_ENCAPSED_STRING),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $source;

    /**
     * @param Token|Node|string|null $source
     */
    public function __construct($source = null)
    {
        parent::__construct();
        if ($source !== null)
        {
            $this->setSource($source);
        }
    }

    /**
     * @param Token|null $source
     * @return static
     */
    public static function __instantiateUnchecked($source)
    {
        $instance = new static();
        $instance->source = $source;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'source' => &$this->source,
        ];
        return $refs;
    }

    public function getSource(): Token
    {
        if ($this->source === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->source;
    }

    public function hasSource(): bool
    {
        return $this->source !== null;
    }

    /**
     * @param Token|Node|string|null $source
     */
    public function setSource($source): void
    {
        if ($source !== null)
        {
            /** @var Token $source */
            $source = NodeConverter::convert($source, Token::class, $this->_phpVersion);
            $source->_attachTo($this);
        }
        if ($this->source !== null)
        {
            $this->source->detach();
        }
        $this->source = $source;
    }
}
