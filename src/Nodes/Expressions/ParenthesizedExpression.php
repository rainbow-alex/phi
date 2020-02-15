<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedParenthesizedExpression;
use Phi\Nodes\Helpers\Key;
use Phi\Nodes\Statements\ForeachStatement;
use Phi\WrapperNode;

/**
 * @implements WrapperNode<Expression>
 */
class ParenthesizedExpression extends Expression implements WrapperNode
{
	use GeneratedParenthesizedExpression;

	public function isConstant(): bool
	{
		return $this->getExpression()->isConstant();
	}

	public function isTemporary(): bool
	{
		return $this->getExpression()->isTemporary();
	}

	protected function extraValidation(int $flags): void
	{
		if ($flags & self::CTX_WRITE)
		{
			$parent = $this->getParent();
			if (
				$parent instanceof ForeachStatement
				|| ($parent instanceof Key && $parent->getParent() instanceof ForeachStatement)
				|| $parent instanceof AssignExpression
				|| $parent instanceof CombinedAssignExpression
				|| $parent instanceof CrementExpression
			)
			{
				throw ValidationException::invalidExpressionInContext($this);
			}
		}

		parent::extraValidation($flags);
	}

	public function convertToPhpParser()
	{
		return $this->getExpression()->convertToPhpParser();
	}

	public function wrapNode(Node $node): void
	{
		$this->setExpression($node);
	}
}
