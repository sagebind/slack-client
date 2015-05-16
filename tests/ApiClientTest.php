<?php
namespace Slack\Tests;

use Slack\ApiClient;
use Slack\Response;

class ApiClientTest extends ClientTestCase
{
    public function testApiCall()
    {
        // add the mock subscriber to the client
        $this->mockResponse(200, [], '{"ok": true}');

        // create the API client
        $client = new ApiClient($this->guzzle);
        $response = $client->apiCall('api.test');

        // exactly one request should have been sent
        $this->assertCount(1, $this->history);

        // verify the sent URL
        $this->assertEquals(ApiClient::BASE_URL.'api.test', $this->history->getLastRequest()->getUrl());

        // verify response
        $this->assertInstanceOf(Response::class, $response);
    }
}
