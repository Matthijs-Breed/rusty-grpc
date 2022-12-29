<?php

namespace PokerHands\Trait;

trait Random
{
    public static function getRandom() {
        $enums = self::cases();
        return $enums[array_rand($enums)]; 
    }

    public static function getRandomValue() {
        return self::getRandom()->value;
    }
}