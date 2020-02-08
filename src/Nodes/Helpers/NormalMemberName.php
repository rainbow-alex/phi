<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Generated\GeneratedNormalMemberName;

class NormalMemberName extends MemberName
{
    use GeneratedNormalMemberName;

    public function convertToPhpParserNode()
    {
        return $this->getToken()->getSource();
    }
}
