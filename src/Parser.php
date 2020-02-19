<?php

declare(strict_types=1);

namespace Phi;

if (getenv('PHI_DISABLE_PARSER_OPT') || defined('__PHPSTAN_RUNNING__'))
{
	require __DIR__ . "/Parser.src.php";
}
else
{
	// note: mark this file as plain text in phpstorm
	require __DIR__ . "/Parser.opt.php";
}
