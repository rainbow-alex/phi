#!/usr/bin/env php
<?php

use Phi\Exception\PhiException;
use Phi\Exception\ValidationException;
use Phi\Lexer;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Token;
use Phi\Util\Console;

require __DIR__ . '/../vendor/autoload.php';

main($argv);

/** @noinspection PhpComposerExtensionStubsInspection */
function main(array $argv)
{
	$commandFlags = [
		"tokens",
		"ast",
		"token-get-all",
		"lint",
		"php-parser-tokens",
		"php-parser-ast",
	];

	$opts = getopt("h", array_merge([
		"phpv:",
		"all",
	], $commandFlags), $argi);
	$argv = array_slice($argv, $argi);

	if (isset($opts["all"]))
	{
		$opts += array_fill_keys($commandFlags, false);
	}

	$phpVersion = isset($opts["phpv"]) ? (int) $opts["phpv"] : PHP_VERSION_ID;
	if (PhpVersion::PHP_7_2 <= $phpVersion && $phpVersion < PhpVersion::PHP_7_3)
	{
		$phpBin = "php7.2";
	}
	else if (PhpVersion::PHP_7_3 <= $phpVersion && $phpVersion < PhpVersion::PHP_7_4)
	{
		$phpBin = "php7.3";
	}
	else if (PhpVersion::PHP_7_4 <= $phpVersion)
	{
		$phpBin = "php7.4";
	}
	else
	{
		throw new \LogicException();
	}

	if (!$argv)
	{
		$tmp = tempnam(sys_get_temp_dir(), "phi_dump");
		file_put_contents($tmp, "<?php\n\n");
		register_shutdown_function(function () use ($tmp)
		{
			unlink($tmp);
		});
		passthru("nano --tempfile --nonewlines --tabsize=4 --autoindent --syntax=php +3 " . escapeshellarg($tmp));
		$argv[] = $tmp;
	}

	foreach ($argv as $arg)
	{
		foreach (expand($arg) as $file)
		{
			echo Console::invert(Console::bold(Console::blue("$file"))) . "\n";

			if (!file_exists($file))
			{
				echo Console::bold(Console::red("$file does not exist.")) . "\n";
				continue;
			}

			$source = file_get_contents($file);

			if (isset($opts["tokens"]))
			{
				echo Console::invert(Console::bold(">>> phi tokens")) . "\n";
				$tokens = (new Lexer($phpVersion))->lex($file, $source);
				foreach ($tokens as $token)
				{
					/** @var Token $token */
					$token->debugDump();
				}
			}

			try
			{
				$ast = (new Parser($phpVersion))->parse($file, $source);
			}
			catch (PhiException $e)
			{
				echo Console::bold(Console::yellow($e->getMessage())) . "\n";
				goto phi_failed;
			}

			if (isset($opts["ast"]))
			{
				echo Console::invert(Console::bold(">>> phi AST")) . "\n";
				$ast->debugDump();
			}

			try
			{
				echo Console::invert(Console::bold(">>> phi validation")) . "\n";
				$ast->validate();
				echo "OK\n";
			}
			catch (ValidationException $e)
			{
				echo Console::bold(Console::yellow($e->getMessage())) . "\n";
			}

			phi_failed:

			if (isset($opts["token-get-all"]))
			{
				echo Console::invert(Console::bold(">>> token_get_all()")) . "\n";
				foreach (@token_get_all($source) as $t)
				{
					if (is_array($t))
					{
						echo token_name($t[0]) . ": " . json_encode($t[1]) . "\n";
					}
					else
					{
						echo json_encode($t) . "\n";
					}
				}
			}

			if (isset($opts["lint"]))
			{
				echo Console::invert(Console::bold(">>> php --syntax-check")) . "\n";
				system($phpBin . " --no-php-ini -d short_open_tag=0 --syntax-check " . escapeshellarg($file));
			}

			if (isset($opts["php-parser-tokens"]))
			{
				echo Console::invert(Console::bold(">>> php-parser tokens")) . "\n";

				try
				{
					$ppLexer = new PhpParser\Lexer\Emulative();
					$ppLexer->startLexing($source);
					$ppTokens = $ppLexer->getTokens();
				}
				catch (Throwable $t)
				{
					echo Console::bold(Console::yellow($t->getMessage())) . "\n";
					continue;
				}

				foreach ($ppTokens as $t)
				{
					if (is_array($t))
					{
						echo token_name($t[0]) . ": " . json_encode($t[1]) . "\n";
					}
					else
					{
						echo json_encode($t) . "\n";
					}
				}
			}

			if (isset($opts["php-parser-ast"]))
			{
				echo Console::invert(Console::bold(">>> php-parser AST")) . "\n";

				try
				{
					$ppAst = (new PhpParser\ParserFactory())->create(PhpParser\ParserFactory::PREFER_PHP7)->parse($source);
				}
				catch (Throwable $t)
				{
					echo Console::bold(Console::yellow($t->getMessage())) . "\n";
					continue;
				}

				echo (new PhpParser\NodeDumper())->dump($ppAst) . "\n";
			}
		}
	}
}

function expand(string $path, bool $recursive = false)
{
	if (is_dir($path))
	{
		foreach (scandir($path) as $f)
		{
			if ($f === '.' || $f === '..')
			{
				continue;
			}

			yield from expand(rtrim($path, '/') . '/' . $f, true);
		}
	}
	else if (!$recursive || preg_match('{\.php$}', $path))
	{
		yield $path;
	}
}
