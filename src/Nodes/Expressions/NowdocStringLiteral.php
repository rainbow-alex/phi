<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedNowdocStringLiteral;
use PhpParser\Node\Scalar\String_;

class NowdocStringLiteral extends ConstantStringLiteral
{
    use GeneratedNowdocStringLiteral;

    public function convertToPhpParserNode()
    {
        $content = $this->getContent();
        return new String_(
            $content ? \substr($content->getSource(), 0, -1) : "" // TODO verify: it seems php-parser trims a newline
        );
    }
}
