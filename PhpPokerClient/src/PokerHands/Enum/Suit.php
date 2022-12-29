<?php

namespace PokerHands\Enum;

use PokerHands\Trait\Random;

enum Suit: int {
    case Hearts     = 0;
    case Diamonds   = 1;
    case Clubs      = 2;
    case Spades     = 3;

    use Random;

    public function symbol() {
        return match($this) {
            Suit::Hearts    => "\u{2660}",
            Suit::Diamonds  => "\u{2665}",
            Suit::Clubs     => "\u{2666}",
            Suit::Spades    => "\u{2663}"
        };
    }
}