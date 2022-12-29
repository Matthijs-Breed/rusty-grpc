<?php

namespace Pokerhands\Enum;

enum Category: int {
    case ROYAL_FLUSH     = 0;
    case STRAIGHT_FLUSH  = 1;
    case FOUR_OF_A_KIND  = 2;
    case FULL_HOUSE      = 3;
    case FLUSH           = 4;
    case STRAIGHT        = 5;
    case THREE_OF_A_KIND = 6;
    case TWO_PAIR        = 7;
    case ONE_PAIR        = 8;
    case HIGH_CARD       = 9;

    public function label() {
        return match($this) {
            Category::ROYAL_FLUSH       => 'Royal Flush',
            Category::STRAIGHT_FLUSH    => 'Straight Flush',
            Category::FOUR_OF_A_KIND    => 'Four of a Kind',
            Category::FULL_HOUSE        => 'Full House',
            Category::FLUSH             => 'Flush',
            Category::STRAIGHT          => 'Straight',
            Category::THREE_OF_A_KIND   => 'Three of a Kind',
            Category::TWO_PAIR          => 'Two Pair',
            Category::ONE_PAIR          => 'One Pair',
            Category::HIGH_CARD         => 'High Card'
        };
    }
}