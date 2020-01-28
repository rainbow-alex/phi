<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedShortArrayExpression;
use PhpParser\Node\Expr\Array_;

// TODO array items with keys can't have empty value
class ShortArrayExpression extends GeneratedShortArrayExpression
{
    public function validateContext(int $flags): void
    {
        if ($flags & self::CTX_READ)
        {
            // (only) in read we keep track of whether the array is constant
            // if it is completely, we will run an additional check on the key types
            $constant = true;

            foreach ($this->getItems() as $item)
            {
                if ($key = $item->getKey())
                {
                    $constant = $constant && ExpressionClassification::isConstant($key->getValue());

                    if (!$constant)
                    {
                        $key->getValue()->validateContext(self::CTX_READ);
                    }
                }

                if ($value = $item->getValue())
                {
                    if ($item->hasByReference())
                    {
                        $constant = false;

                        $value->validateContext(self::CTX_READ | self::CTX_ALIAS_WRITE);
                    }
                    else
                    {
                        $constant = $constant && ExpressionClassification::isConstant($value);

                        if (!$constant)
                        {
                            $value->validateContext(self::CTX_READ);
                        }
                    }
                }
                else
                {
                    throw ValidationException::expressionContext(self::CTX_READ, $this);
                }
            }

            if ($constant)
            {
                foreach ($this->getItems() as $item)
                {
                    if ($key = $item->getKey())
                    {
                        if (!ExpressionClassification::isKey($key->getValue()))
                        {
                            throw new ValidationException(__METHOD__, $key->getValue());
                        }
                    }
                }
            }
        }
        else if ($flags & self::CTX_WRITE)
        {
            $empty = true;

            foreach ($this->getItems() as $item) /** @var ArrayItem $item */
            {
                if ($key = $item->getKey())
                {
                    $key->getValue()->validateContext(self::CTX_READ);
                    // note with write the key check isn't done
                }

                if ($byRef = $item->getByReference())
                {
                    throw ValidationException::expressionContext(self::CTX_WRITE, $byRef);
                }

                if ($value = $item->getValue())
                {
                    $empty = false;
                    $value->validateContext(self::CTX_WRITE);
                }
                else
                {
                    // empty value is ok for write
                }
            }

            if ($empty)
            {
                throw ValidationException::expressionContext(self::CTX_WRITE, $this);
            }
        }

        $never = self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($never, $this);
        }
    }

    public function convertToPhpParserNode()
    {
        $items = [];
        foreach ($this->getItems() as $phiItem)
        {
            $items[] = $phiItem->convertToPhpParserNode();
        }
        return new Array_($items);
    }
}
