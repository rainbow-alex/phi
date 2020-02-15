<?php

declare(strict_types=1);

namespace Phi\Exception;

class LiteralParsingException extends PhiException
{
	public function __construct()
	{
		parent::__construct("Invalid literal", null);
	}
}
