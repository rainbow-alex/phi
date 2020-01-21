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

abstract class GeneratedStaticVariableDeclaration extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_STATIC),
                'variables' => new And_(new EachItem(new IsInstanceOf(Nodes\StaticVariable::class)), new EachSeparator(new IsToken(','))),
                'semiColon' => new Optional(new IsToken(';')),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var SeparatedNodesList|Nodes\StaticVariable[]
     */
    private $variables;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->variables = new SeparatedNodesList();
    }

    /**
     * @param Token|null $keyword
     * @param mixed[] $variables
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $variables, $semiColon)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->variables->__initUnchecked($variables);
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'variables' => &$this->variables,
            'semiColon' => &$this->semiColon,
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

    /**
     * @return SeparatedNodesList|Nodes\StaticVariable[]
     */
    public function getVariables(): SeparatedNodesList
    {
        return $this->variables;
    }

    /**
     * @param Nodes\StaticVariable $variabl
     */
    public function addVariabl($variabl): void
    {
        /** @var Nodes\StaticVariable $variabl */
        $variabl = NodeConverter::convert($variabl, Nodes\StaticVariable::class);
        $this->variables->add($variabl);
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
