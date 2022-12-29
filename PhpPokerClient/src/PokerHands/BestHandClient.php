<?php
// GENERATED CODE -- DO NOT EDIT!

namespace PokerHands;

/**
 */
class BestHandClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \PokerHands\HandRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Hand(\PokerHands\HandRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/pokerhands.BestHand/Hand',
        $argument,
        ['\PokerHands\BestHandResult', 'decode'],
        $metadata, $options);
    }

}
