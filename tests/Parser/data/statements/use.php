<?php

/** @noinspection ALL */

use A1; // RegularUseStatement(use, [UseName(RegularName([A1]))], ;)
use A2\A3; // RegularUseStatement(use, [UseName(RegularName([A2, \, A3]))], ;)
use A4\A5\A6; // RegularUseStatement(use, [UseName(RegularName([A4, \, A5, \, A6]))], ;)
use \A7; // RegularUseStatement(use, [UseName(RegularName([\, A7]))], ;)
use \A8\A9; // RegularUseStatement(use, [UseName(RegularName([\, A8, \, A9]))], ;)
use \A10\A11\A12; // RegularUseStatement(use, [UseName(RegularName([\, A10, \, A11, \, A12]))], ;)

use B1 as B2; // RegularUseStatement(use, [UseName(RegularName([B1]), UseAlias(as, B2))], ;)
use B3\B4 as B5; // RegularUseStatement(use, [UseName(RegularName([B3, \, B4]), UseAlias(as, B5))], ;)
use \B6 as B7; // RegularUseStatement(use, [UseName(RegularName([\, B6]), UseAlias(as, B7))], ;)
use \B8\B9 as B10; // RegularUseStatement(use, [UseName(RegularName([\, B8, \, B9]), UseAlias(as, B10))], ;)

use C1, C2; // RegularUseStatement(use, [UseName(RegularName([C1])), ,, UseName(RegularName([C2]))], ;)
use C3\C4, \C5; // RegularUseStatement(use, [UseName(RegularName([C3, \, C4])), ,, UseName(RegularName([\, C5]))], ;)
use C6 as C7, C8\C9 as C10; // RegularUseStatement(use, [UseName(RegularName([C6]), UseAlias(as, C7)), ,, UseName(RegularName([C8, \, C9]), UseAlias(as, C10))], ;)

use D1\{D2, D3\D4}; // GroupedUseStatement(use, GroupedUsePrefix(RegularName([D1]), \), {, [UseName(RegularName([D2])), ,, UseName(RegularName([D3, \, D4]))], }, ;)
use D5\D6\{D7, D8 as D9}; // GroupedUseStatement(use, GroupedUsePrefix(RegularName([D5, \, D6]), \), {, [UseName(RegularName([D7])), ,, UseName(RegularName([D8]), UseAlias(as, D9))], }, ;)
use \D10\{D11, D12}; // GroupedUseStatement(use, GroupedUsePrefix(RegularName([\, D10]), \), {, [UseName(RegularName([D11])), ,, UseName(RegularName([D12]))], }, ;)
use \D13\{D14, D15,}; // GroupedUseStatement(use, GroupedUsePrefix(RegularName([\, D13]), \), {, [UseName(RegularName([D14])), ,, UseName(RegularName([D15])), ,], }, ;)
use {D16, D17\D18}; // GroupedUseStatement(use, {, [UseName(RegularName([D16])), ,, UseName(RegularName([D17, \, D18]))], }, ;)

use function E1; // RegularUseStatement(use, function, [UseName(RegularName([E1]))], ;)
use function E2\E3; // RegularUseStatement(use, function, [UseName(RegularName([E2, \, E3]))], ;)
use function \E4\E5; // RegularUseStatement(use, function, [UseName(RegularName([\, E4, \, E5]))], ;)
use function {E6, E7 as E8}; // GroupedUseStatement(use, function, {, [UseName(RegularName([E6])), ,, UseName(RegularName([E7]), UseAlias(as, E8))], }, ;)
use function E9\{E10, E11 as E12}; // GroupedUseStatement(use, function, GroupedUsePrefix(RegularName([E9]), \), {, [UseName(RegularName([E10])), ,, UseName(RegularName([E11]), UseAlias(as, E12))], }, ;)

use const F1; // RegularUseStatement(use, const, [UseName(RegularName([F1]))], ;)
use const F2\F3; // RegularUseStatement(use, const, [UseName(RegularName([F2, \, F3]))], ;)
use const \F4\F5; // RegularUseStatement(use, const, [UseName(RegularName([\, F4, \, F5]))], ;)
use const {F6, F7 as F8}; // GroupedUseStatement(use, const, {, [UseName(RegularName([F6])), ,, UseName(RegularName([F7]), UseAlias(as, F8))], }, ;)
use const F9\{F10, F11 as F12}; // GroupedUseStatement(use, const, GroupedUsePrefix(RegularName([F9]), \), {, [UseName(RegularName([F10])), ,, UseName(RegularName([F11]), UseAlias(as, F12))], }, ;)
