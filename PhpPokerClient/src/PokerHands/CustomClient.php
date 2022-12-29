<?php

namespace PokerHands;

use Grpc\BaseStub;
use Grpc\Channel;
use Grpc\ChannelCredentials;
use PokerHands\BestHandResult;
use PokerHands\BestHandResultExt;
use PokerHands\Enum\Suit;
use PokerHands\Enum\Rank;
use PokerHands\HandRequest;
use PokerHands\HandRequest\HandCard;
//use PokerHands\HandRequest\HandCard\Rank;
//use PokerHands\HandRequest\HandCard\Suit;


class CustomClient extends BaseStub {


    const LOCAL_SERVICE_HOST = "[::1]:50051";

    public function __construct(
        string $hostname = self::LOCAL_SERVICE_HOST,
        ?array $opts = null,        
        ?Channel $channel = null
    ) {
        if (!$opts) {
            $opts = [
                'credentials' => ChannelCredentials::createInsecure()
            ];
        }
        parent::__construct($hostname, $opts, $channel);
    }

    public function Hand(
        HandRequest $handRequest,
        $metaData = [],
        $options =[]
    ): BestHandResult {

        foreach ($handRequest->getCards() as $card) {
            $this->validateCardValues($card);
        }

        [$result, $status] = $this->_simpleRequest(
            '/pokerhands.BestHand/Hand',
            $handRequest,
            [
                BestHandResult::class,
                'decode'
            ],
            $metaData,
            $options
        )->wait();
        if (!$result) {
            throw new \Exception("Error: {$status->code} - {$status->details}");
        }
        return $result;
    }

    protected function validateCardValues(HandCard $card)
    {
        Rank::from($card->getRank());
        Suit::from($card->getSuit());
    }
}