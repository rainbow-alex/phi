<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart;
use Phi\Nodes\Generated\GeneratedDoubleQuotedStringLiteral;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\String_;

class DoubleQuotedStringLiteral extends InterpolatedStringLiteral
{
    use GeneratedDoubleQuotedStringLiteral;

    public function convertToPhpParserNode()
    {
        $parts = $this->getParts()->getItems();
        if (count($parts) === 0)
        {
            return new String_("");
        }
        else if (count($parts) === 1 && $parts[0] instanceof ConstantInterpolatedStringPart)
        {
            /** @var ConstantInterpolatedStringPart $part */
            $part = $parts[0];
            // TODO this static method is internal, figure it out somehow?
            return new String_(String_::parseEscapeSequences($part->getContent()->getSource(), '"', true));
        }
        else
        {
            return new Encapsed($this->parts->convertToPhpParserNode());
        }
    }
}
