<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expressions\StringInterpolation\ConstantInterpolatedStringPart;
use Phi\Nodes\Generated\GeneratedHeredocStringLiteral;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\String_;

class HeredocStringLiteral extends InterpolatedStringLiteral
{
    use GeneratedHeredocStringLiteral;

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
            $content = \substr($part->getContent()->getSource(), 0, -1); // TODO verify: it seems php-parser trims a newline
            // TODO this static method is internal, figure it out somehow?
            // TODO fix quote type
            return new String_(String_::parseEscapeSequences($content, '"', true));
        }
        else
        {
            return new Encapsed($this->parts->convertToPhpParserNode());
        }
    }
}
