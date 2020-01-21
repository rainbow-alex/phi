<?php

/** @noinspection ALL */

return; // ReturnStatement(return, ;)
return 1; // ReturnStatement(return, 1, ;)
return // ReturnStatement(return)
; // NopStatement(;)
return 3 // ReturnStatement(return, 3)
; // NopStatement(;)

// errors
# { return } // 1:10 - Expected expression, got "}"
# { return 3 } // 1:12 - Unexpected "}"
