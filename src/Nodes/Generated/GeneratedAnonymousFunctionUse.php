<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedAnonymousFunctionUse
{
    /**
     * @var \Phi\Token|null
     */
    private $keyword;

    /**
     * @var \Phi\Token|null
     */
    private $leftParenthesis;

    /**
     * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expressions\AnonymousFunctionUseBinding[]
     * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expressions\AnonymousFunctionUseBinding>
     */
    private $bindings;

    /**
     * @var \Phi\Token|null
     */
    private $rightParenthesis;

    /**
     */
    public function __construct()
    {
        $this->bindings = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expressions\AnonymousFunctionUseBinding::class);
    }

    /**
     * @param \Phi\Token $keyword
     * @param \Phi\Token $leftParenthesis
     * @param mixed[] $bindings
     * @param \Phi\Token $rightParenthesis
     * @return self
     */
    public static function __instantiateUnchecked($keyword, $leftParenthesis, $bindings, $rightParenthesis)
    {
        $instance = new self;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->bindings->__initUnchecked($bindings);
        $instance->bindings->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->keyword,
            $this->leftParenthesis,
            $this->bindings,
            $this->rightParenthesis,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->keyword === $childToDetach)
        {
            return $this->keyword;
        }
        if ($this->leftParenthesis === $childToDetach)
        {
            return $this->leftParenthesis;
        }
        if ($this->rightParenthesis === $childToDetach)
        {
            return $this->rightParenthesis;
        }
        throw new \LogicException();
    }

    public function getKeyword(): \Phi\Token
    {
        if ($this->keyword === null)
        {
            throw TreeException::missingNode($this, "keyword");
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var \Phi\Token $keyword */
            $keyword = NodeCoercer::coerce($keyword, \Phi\Token::class, $this->getPhpVersion());
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getLeftParenthesis(): \Phi\Token
    {
        if ($this->leftParenthesis === null)
        {
            throw TreeException::missingNode($this, "leftParenthesis");
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var \Phi\Token $leftParenthesis */
            $leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $leftParenthesis->detach();
            $leftParenthesis->parent = $this;
        }
        if ($this->leftParenthesis !== null)
        {
            $this->leftParenthesis->detach();
        }
        $this->leftParenthesis = $leftParenthesis;
    }

    /**
     * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expressions\AnonymousFunctionUseBinding[]
     * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expressions\AnonymousFunctionUseBinding>
     */
    public function getBindings(): \Phi\Nodes\Base\SeparatedNodesList
    {
        return $this->bindings;
    }

    public function getRightParenthesis(): \Phi\Token
    {
        if ($this->rightParenthesis === null)
        {
            throw TreeException::missingNode($this, "rightParenthesis");
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var \Phi\Token $rightParenthesis */
            $rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    public function _validate(int $flags): void
    {
        if ($this->keyword === null)
            throw ValidationException::missingChild($this, "keyword");
        if ($this->leftParenthesis === null)
            throw ValidationException::missingChild($this, "leftParenthesis");
        if ($this->rightParenthesis === null)
            throw ValidationException::missingChild($this, "rightParenthesis");
        if ($this->keyword->getType() !== 253)
            throw ValidationException::invalidSyntax($this->keyword, [253]);
        if ($this->leftParenthesis->getType() !== 105)
            throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
        foreach ($this->bindings->getSeparators() as $t)
            if ($t && $t->getType() !== 109)
                throw ValidationException::invalidSyntax($t, [109]);
        if ($this->rightParenthesis->getType() !== 106)
            throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


        $this->extraValidation($flags);

        foreach ($this->bindings as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        foreach ($this->bindings as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
