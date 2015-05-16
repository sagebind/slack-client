<?php
namespace Slack\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Subscriber\History;
use Slack\ApiClient;

class ApiClientTest extends \PHPUnit_Framework_TestCase
{
    public function testApiCall()
    {
        $httpClient = new Client();

        // add history subscriber to the client
        $history = new History();
        $httpClient->getEmitter()->attach($history);

        // ddd the mock subscriber to the client
        $httpClient->getEmitter()->attach(new Mock([
            MessageFactory::createResponse(200, [], '{"ok": true}'),
        ]));

        // create the API client
        $client = new ApiClient($httpClient);
        $client->apiCall('api.test');

        // exactly one request should have been sent
        $this->assertCount(1, $history);

        // verify the sent URL
        $this->assertEquals(ApiClient::BASE_URL.'api.test', $history->getLastRequest()->getUrl());
    }
}
