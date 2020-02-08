#!/usr/bin/env php
<?php

use Phi\Exception\PhiException;
use Phi\Exception\ValidationException;
use Phi\Parser;
use Phi\PhpVersion;
use Phi\Util\Console;

require __DIR__ . '/../vendor/autoload.php';

foreach (array_slice($argv, 1) as $arg)
{
    dump($arg);
}

function dump(string $path, bool $recursive = false): void
{
    if (is_dir($path))
    {
        foreach (scandir($path) as $f)
        {
            if ($f === '.' || $f === '..')
            {
                continue;
            }

            dump(rtrim($path, '/') . '/' . $f, true);
        }
    }
    else if ((!$recursive || preg_match('{\.php$}', $path)) && file_exists($path))
    {
        echo Console::bold(">>> $path") . "\n";

        $source = file_get_contents($path);

        try
        {
            $ast = (new Parser(PhpVersion::PHP_7_2))->parse($path, $source);
        }
        catch (PhiException $e)
        {
            echo Console::bold(">>> $path") . "\n";
            echo Console::bold(Console::yellow($e->getMessageWithContext())) . "\n";
            sleep(1);
            return;
        }
        catch (\Throwable $e)
        {
            echo Console::bold(">>> $path") . "\n";
            echo Console::bold(Console::red(get_class($e) . ': ' . $e->getMessage())) . "\n";
            sleep(1);
            return;
        }

        try
        {
            $ast->validate();
        }
        catch (ValidationException $e)
        {
            echo Console::bold(">>> $path") . "\n";
            echo Console::bold(Console::yellow($e->getMessageWithContext())) . "\n";
            sleep(1);
            return;
        }

//        $ast->debugDump();
    }
}
