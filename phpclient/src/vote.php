<?php 
declare (strict_types=1);

include (__DIR__ . '/../vendor/autoload.php');

use Voting\VotingRequest;
use Voting\VotingClient;
use Voting\VotingRequest\Vote;
use Grpc\ChannelCredentials;

class Voter 
{
    /**
     * Exact syntax is important, otherwise connection will be refused
     */
    const LOCAL_SERVICE_HOST = "[::1]:50051";

    protected VotingClient $votingClient;

    public function __construct() {
        $this->votingClient = new VotingClient(
            self::LOCAL_SERVICE_HOST, 
            [
                'credentials' => ChannelCredentials::createInsecure()
            ]
        );
    }

    /**
     * @param string $url
     * @param bool $up
     * @return string
     */
    public function doRequest(string $url, bool $up = true): string
    {
        $votingRequest = new VotingRequest([
            'url'   => $url,
            'vote'  => $up ? Vote::UP : Vote::DOWN
        ]);
        [$response, $status] = $this->votingClient->Vote($votingRequest)->wait();
        if ($response) {
            return $response->getConfirmation();
        } else {
            return $status->getDetails();
        }
    }
}

$voter = new Voter();
echo $voter->doRequest('https://experius.nl/', true) . PHP_EOL;