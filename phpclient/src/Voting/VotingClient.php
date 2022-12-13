<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Voting;

/**
 * *
 * Define a service Voting, which takes a message Vote and returns a VotingResponse
 */
class VotingClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Voting\VotingRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Vote(\Voting\VotingRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/voting.Voting/Vote',
        $argument,
        ['\Voting\VotingResponse', 'decode'],
        $metadata, $options);
    }

}
