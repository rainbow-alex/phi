<?php

namespace Phi\Tests;

use Phi\Exception\PhiException;
use Phi\Parser;
use Phi\PhpVersion;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

class SystemTest extends TestCase
{
    /**
     * @dataProvider files_to_parse
     */
    public function test_parse_self(string $path): void
    {
        $source = \file_get_contents($path);
        $ast = (new Parser(PhpVersion::PHP_7_2))->parse($path, $source);
        try
        {
            $ast->validate();
        }
        catch (PhiException $e)
        {
            throw new \RuntimeException($e->getMessageWithContext());
        }
        $this->assertSame($source, (string) $ast);
    }

    public function files_to_parse()
    {
        $files = (new Finder())->in(__DIR__ . '/../src')->filter(function (\SplFileInfo $p)
        {
            return $p->getExtension() === 'php';
        });

        foreach ($files as $file)
        {
            /** @var \SplFileInfo $file */
            yield $file->getFilename() => [(string) $file];
        }
    }
}
