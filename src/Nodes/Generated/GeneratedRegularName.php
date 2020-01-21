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

abstract class GeneratedRegularName extends CompoundNode implements Nodes\Name
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'parts' => new And_(new EachItem(new And_(new IsInstanceOf(Token::class), new IsToken(\T_STRING))), new EachSeparator(new IsToken(\T_NS_SEPARATOR))),
            ]),
        ];
    }

    /**
     * @var SeparatedNodesList|Token[]
     */
    private $parts;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->parts = new SeparatedNodesList();
    }

    /**
     * @param mixed[] $parts
     * @return static
     */
    public static function __instantiateUnchecked($parts)
    {
        $instance = new static();
        $instance->parts->__initUnchecked($parts);
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'parts' => &$this->parts,
        ];
        return $refs;
    }

    /**
     * @return SeparatedNodesList|Token[]
     */
    public function getParts(): SeparatedNodesList
    {
        return $this->parts;
    }

    /**
     * @param Token $part
     */
    public function addPart($part): void
    {
        /** @var Token $part */
        $part = NodeConverter::convert($part, Token::class);
        $this->parts->add($part);
    }
}
