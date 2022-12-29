<?php

declare (strict_types=1);
include (__DIR__ . '/vendor/autoload.php');

use Grpc\ChannelCredentials;
use PokerHands\BestHandClient;
use PokerHands\CustomClient;
use PokerHands\Enum\Category;
use PokerHands\Enum\Suit;
use PokerHands\Enum\Rank;
use PokerHands\HandRequest;
use PokerHands\HandRequest\HandCard;

$client = new BestHandClient(
    "[::1]:50051",
    [
        'credentials' => ChannelCredentials::createInsecure()
    ]
);
$customClient = new CustomClient();

$cards = [];

function generateCard(): HandCard {
    return new HandCard([
        'rank' => Rank::getRandomValue(),
        'suit' => Suit::getRandomValue()
    ]);
}

function isInList(HandCard $card, array $cards) {
    foreach ($cards as $_card) {
        if ($card->getRank() == $_card->getRank() && $card->getSuit() == $_card->getSuit()) {
            return true;
        }
        return false;
    }
}

for ($i = 0; $i < 8; $i++) {
    $card = generateCard();
    while (isInList($card, $cards)) {
        $card = generateCard();
    }
    $cards[] = $card;
}

// $cards = [
//     new HandCard([
//         'rank' => 10,
//         'suit' => 0
//     ]),
//     new HandCard([
//         'rank' => 11,
//         'suit' => 0
//     ]),
//     new HandCard([
//         'rank' => 12,
//         'suit' => 0
//     ]),
//     new HandCard([
//         'rank' => 13,
//         'suit' => 0
//     ]),
//     new HandCard([
//         'rank' => 14,
//         'suit' => 0
//     ])
// ];

foreach ($cards as $card) {
    echo Rank::from($card->getRank())->name .  " of " . Suit::from($card->getSuit())->symbol() . PHP_EOL;
}

$request = new HandRequest(
    [
        'cards' => $cards
    ]
);

$result = $customClient->Hand($request);
echo PHP_EOL;
echo Category::from($result->getCategory())->label() . ":" .  PHP_EOL;
foreach ($result->getCards() as $card) {
    echo Rank::from($card->getRank())->name .  " of " . Suit::from($card->getSuit())->symbol() . PHP_EOL;
}