<?php

declare(strict_types=1);

namespace Phi;

if (getenv('PHI_DISABLE_PARSER_OPT') || defined('__PHPSTAN_RUNNING__'))
{
	require __DIR__ . "/Parser.src.php";
}
else
{
	require __DIR__ . "/_optimized/Parser.php";
}
