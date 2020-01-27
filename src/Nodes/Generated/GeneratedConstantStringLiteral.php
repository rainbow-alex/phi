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

abstract class GeneratedConstantStringLiteral extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $source;

    /**
     * @param Token|Node|string|null $source
     */
    public function __construct($source = null)
    {
        if ($source !== null)
        {
            $this->setSource($source);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $source
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $source)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->source = $source;
        $instance->source->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
            $source = NodeConverter::convert($source, Token::class, $this->phpVersion);
            $source->detach();
            $source->parent = $this;
        }
        if ($this->source !== null)
        {
            $this->source->detach();
        }
        $this->source = $source;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->source === null) throw ValidationException::childRequired($this, 'source');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
