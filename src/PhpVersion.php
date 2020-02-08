<?php

declare(strict_types=1);

namespace Phi;

class PhpVersion
{
    public const PHP_7_2 = 70200;
    public const PHP_7_3 = 70300;
    public const PHP_7_4 = 70400;

    public static function DEFAULT(): int
    {
        return self::PHP_7_2;
    }

    public static function validate(int $phpVersion): void
    {
        if ($phpVersion !== self::PHP_7_2)
        {
            // *for now*
            throw new \InvalidArgumentException('Only php 7.2 is supported');
        }
    }
}
