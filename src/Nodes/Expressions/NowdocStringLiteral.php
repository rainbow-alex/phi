<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedNowdocStringLiteral;
use Phi\Nodes\ValidationTraits\DocStringLiteral;
use PhpParser\Node\Scalar\String_;

class NowdocStringLiteral extends ConstantStringLiteral
{
	use GeneratedNowdocStringLiteral;
	use DocStringLiteral;

	protected function extraValidation(int $flags): void
	{
		$this->validateEndDelimiter();
	}

	public function convertToPhpParser()
	{
		$content = $this->getContent();
		return new String_(
			$content ? \substr($content->getSource(), 0, -1) : "" // trim 1 newline
		);
	}
}
