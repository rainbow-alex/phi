<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedDefault;

class Default_ extends GeneratedDefault
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            if (!ExpressionClassification::isConstant($this->getValue()))
            {
                throw new ValidationException(__METHOD__, $this->getValue());
            }
        }
    }
}
