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

abstract class GeneratedStaticVariableDeclaration extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var SeparatedNodesList|Nodes\StaticVariable[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\StaticVariable>
     */
    private $variables;

    /**
     * @var Token|null
     */
    private $delimiter;


    /**
     */
    public function __construct()
    {
        $this->variables = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param mixed[] $variables
     * @param Token $delimiter
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $variables, $delimiter)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->variables->__initUnchecked($variables);
        $instance->variables->parent = $instance;
        $instance->delimiter = $delimiter;
        $delimiter->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "variables" => &$this->variables,
            "delimiter" => &$this->delimiter,
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

    /**
     * @return SeparatedNodesList|Nodes\StaticVariable[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\StaticVariable>
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
        $variabl = NodeConverter::convert($variabl, Nodes\StaticVariable::class, $this->phpVersion);
        $this->variables->add($variabl);
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
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
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
        $this->variables->_validate($flags);
    }
}
