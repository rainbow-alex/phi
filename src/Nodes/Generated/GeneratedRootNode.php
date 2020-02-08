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

trait GeneratedRootNode
{
    /**
     * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
     * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
     */
    private $statements;

    /**
     * @var \Phi\Token|null
     */
    private $eof;

    /**
     * @param \Phi\Nodes\Statement $statement
     */
    public function __construct($statement = null)
    {
        $this->statements = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statement::class);
        if ($statement !== null)
        {
            $this->statements->add($statement);
        }
    }

    /**
     * @param mixed[] $statements
     * @param \Phi\Token|null $eof
     * @return self
     */
    public static function __instantiateUnchecked($statements, $eof)
    {
        $instance = new self;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        $instance->eof = $eof;
        if ($eof) $eof->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->statements,
            $this->eof,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->eof === $childToDetach)
        {
            return $this->eof;
        }
        throw new \LogicException();
    }

    /**
     * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
     * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
     */
    public function getStatements(): \Phi\Nodes\Base\NodesList
    {
        return $this->statements;
    }

    public function getEof(): ?\Phi\Token
    {
        return $this->eof;
    }

    public function hasEof(): bool
    {
        return $this->eof !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $eof
     */
    public function setEof($eof): void
    {
        if ($eof !== null)
        {
            /** @var \Phi\Token $eof */
            $eof = NodeCoercer::coerce($eof, \Phi\Token::class, $this->getPhpVersion());
            $eof->detach();
            $eof->parent = $this;
        }
        if ($this->eof !== null)
        {
            $this->eof->detach();
        }
        $this->eof = $eof;
    }

    public function _validate(int $flags): void
    {
        if ($this->eof)
        if ($this->eof->getType() !== 999)
            throw ValidationException::invalidSyntax($this->eof, [999]);


        $this->extraValidation($flags);

        foreach ($this->statements as $t)
            $t->_validate(0);
    }

    public function _autocorrect(): void
    {
        foreach ($this->statements as $t)
            $t->_autocorrect();

        $this->extraAutocorrect();
    }
}
