<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\PhpVersion;
use Phi\Token;

trait DocStringLiteral
{
	abstract public function getPhpVersion(): int;
	abstract public function getRightDelimiter(): Token;

	private function validateEndDelimiter(): void
	{
		if ($this->getPhpVersion() < PhpVersion::PHP_7_3)
		{
			$end = $this->getRightDelimiter();
			$nextBytes = $end->getRightWhitespace();

			$nextToken = $end->getNextToken();
			if ($nextToken)
			{
				$nextBytes .= $nextToken->toPhp();
				$nextToken = $nextToken->getNextToken();
				if ($nextToken)
				{
					$nextBytes .= $nextToken->getLeftWhitespace();
				}
			}

			if (!\preg_match('{^;?(\r?\n)}', $nextBytes))
			{
				throw ValidationException::invalidSyntax($semi ?? $end);
			}
		}
	}
}
