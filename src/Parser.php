<?php

namespace Phi;

if (true) // TODO allow env var switch
{
    require __DIR__ . "/_optimized/Parser.php";
}
else
{
    require __DIR__ . "/Parser.src.php";
}
