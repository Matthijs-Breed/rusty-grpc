<?php

namespace PokerHands\Enum;

use PokerHands\Trait\Random;

enum Rank: int
{
    case Two     = 2;
    case Three   = 3;
    case Four    = 4;
    case Five    = 5;
    case Six     = 6;
    case Seven   = 7;
    case Eight   = 8;
    case Nine    = 9;
    case Ten     = 10;
    case Jack    = 11;
    case Queen   = 12;
    case King    = 13;
    case Ace     = 14;

    use Random;
}