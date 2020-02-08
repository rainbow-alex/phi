<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\DoubleQuotedStringLiteral;
use Phi\Nodes\Generated\GeneratedConstantInterpolatedStringPart;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;

class ConstantInterpolatedStringPart extends InterpolatedStringPart
{
    use GeneratedConstantInterpolatedStringPart;

    protected function extraValidation(int $flags): void
    {
        // TODO extract this and make string classes do it
        $parent = $this->getParent();
        if ($parent && $parent->getParent() instanceof DoubleQuotedStringLiteral)
        {
            \preg_match_all('{(?<=^|[^\\\\])(?:\\\\{2})*\\\\u\\{([A-Za-z0-f]+)\\}}', $this->getContent()->getSource(), $m);
            foreach ($m[1] as $u)
            {
                if (hexdec($u) > 0x10FFFF)
                {
                    throw ValidationException::invalidSyntax($this);
                }
            }
        }
    }

    public function convertToPhpParserNode()
    {
        // TODO parsing depends on the parent node?
        return new EncapsedStringPart(String_::parseEscapeSequences($this->getContent()->getSource(), '"', true));
    }
}
