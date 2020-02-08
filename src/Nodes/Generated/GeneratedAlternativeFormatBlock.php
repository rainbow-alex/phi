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

trait GeneratedAlternativeFormatBlock
{
    /**
     * @var \Phi\Token|null
     */
    private $colon;

    /**
     * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
     * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
     */
    private $statements;

    /**
     * @var \Phi\Token|null
     */
    private $endKeyword;

    /**
     * @var \Phi\Token|null
     */
    private $delimiter;

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
     * @param \Phi\Token $colon
     * @param mixed[] $statements
     * @param \Phi\Token|null $endKeyword
     * @param \Phi\Token|null $delimiter
     * @return self
     */
    public static function __instantiateUnchecked($colon, $statements, $endKeyword, $delimiter)
    {
        $instance = new self;
        $instance->colon = $colon;
        $colon->parent = $instance;
        $instance->statements->__initUnchecked($statements);
        $instance->statements->parent = $instance;
        $instance->endKeyword = $endKeyword;
        if ($endKeyword) $endKeyword->parent = $instance;
        $instance->delimiter = $delimiter;
        if ($delimiter) $delimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->colon,
            $this->statements,
            $this->endKeyword,
            $this->delimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->colon === $childToDetach)
        {
            return $this->colon;
        }
        if ($this->endKeyword === $childToDetach)
        {
            return $this->endKeyword;
        }
        if ($this->delimiter === $childToDetach)
        {
            return $this->delimiter;
        }
        throw new \LogicException();
    }

    public function getColon(): \Phi\Token
    {
        if ($this->colon === null)
        {
            throw TreeException::missingNode($this, "colon");
        }
        return $this->colon;
    }

    public function hasColon(): bool
    {
        return $this->colon !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $colon
     */
    public function setColon($colon): void
    {
        if ($colon !== null)
        {
            /** @var \Phi\Token $colon */
            $colon = NodeCoercer::coerce($colon, \Phi\Token::class, $this->getPhpVersion());
            $colon->detach();
            $colon->parent = $this;
        }
        if ($this->colon !== null)
        {
            $this->colon->detach();
        }
        $this->colon = $colon;
    }

    /**
     * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
     * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
     */
    public function getStatements(): \Phi\Nodes\Base\NodesList
    {
        return $this->statements;
    }

    public function getEndKeyword(): ?\Phi\Token
    {
        return $this->endKeyword;
    }

    public function hasEndKeyword(): bool
    {
        return $this->endKeyword !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $endKeyword
     */
    public function setEndKeyword($endKeyword): void
    {
        if ($endKeyword !== null)
        {
            /** @var \Phi\Token $endKeyword */
            $endKeyword = NodeCoercer::coerce($endKeyword, \Phi\Token::class, $this->getPhpVersion());
            $endKeyword->detach();
            $endKeyword->parent = $this;
        }
        if ($this->endKeyword !== null)
        {
            $this->endKeyword->detach();
        }
        $this->endKeyword = $endKeyword;
    }

    public function getDelimiter(): ?\Phi\Token
    {
        return $this->delimiter;
    }

    public function hasDelimiter(): bool
    {
        return $this->delimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $delimiter
     */
    public function setDelimiter($delimiter): void
    {
        if ($delimiter !== null)
        {
            /** @var \Phi\Token $delimiter */
            $delimiter = NodeCoercer::coerce($delimiter, \Phi\Token::class, $this->getPhpVersion());
            $delimiter->detach();
            $delimiter->parent = $this;
        }
        if ($this->delimiter !== null)
        {
            $this->delimiter->detach();
        }
        $this->delimiter = $delimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->colon === null)
            throw ValidationException::missingChild($this, "colon");
        if ($this->colon->getType() !== 113)
            throw ValidationException::invalidSyntax($this->colon, [113]);
        if ($this->endKeyword)
        if (!\in_array($this->endKeyword->getType(), [169, 170, 171, 172, 173, 174], true))
            throw ValidationException::invalidSyntax($this->endKeyword, [169, 170, 171, 172, 173, 174]);
        if ($this->delimiter)
        if (!\in_array($this->delimiter->getType(), [114, 143], true))
            throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


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
