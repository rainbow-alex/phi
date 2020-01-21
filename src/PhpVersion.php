<?php

namespace Phi;

class PhpVersion
{
    public const PHP_5_6 = 50600;
    public const PHP_7_2 = 70200;
    public const PHP_7_3 = 70300;
    public const PHP_7_4 = 70400;

    public static function DEFAULT(): int
    {
        return self::PHP_7_2; // TODO!!
    }
}
