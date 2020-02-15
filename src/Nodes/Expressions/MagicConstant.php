<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedMagicConstant;
use Phi\TokenType;
use PhpParser\Node\Scalar\MagicConst;

class MagicConstant extends Expression
{
	use GeneratedMagicConstant;

	public function isConstant(): bool
	{
		return true;
	}

	public function convertToPhpParser()
	{
		switch ($this->getToken()->getType())
		{
			case TokenType::T_FUNC_C: return new MagicConst\Function_();
			case TokenType::T_CLASS_C: return new MagicConst\Class_();
			case TokenType::T_METHOD_C: return new MagicConst\Method();
			case TokenType::T_NS_C: return new MagicConst\Namespace_();
			case TokenType::T_TRAIT_C: return new MagicConst\Trait_();
			case TokenType::T_FILE: return new MagicConst\File();
			case TokenType::T_DIR: return new MagicConst\Dir();
			case TokenType::T_LINE: return new MagicConst\Line();
			default:
				throw new \LogicException();
		}
	}
}
