<?php

namespace Phi\Meta\Validators;

interface NodeValidator
{
    public function getFlag(): int;
    public function validate(string $var): string;
}
