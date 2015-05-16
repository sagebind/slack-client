<?php
namespace Slack\Tests;

use Slack\ApiClient;
use Slack\Response;
use Slack\Team;

class ApiClientTest extends ClientTestCase
{
    protected $client;

    public function setUp()
    {
        parent::setUp();

        // create the API client
        $this->client = new ApiClient($this->guzzle);
    }

    public function testGetTeam()
    {
        $this->mockResponse(200, [], [
            'ok' => true,
            'team' => [
                'id' => $this->faker->randomAscii,
            ],
        ]);

        $team = $this->client->getTeam();
        $this->assertLastRequestUrl(ApiClient::BASE_URL.'team.info');
        $this->assertInstanceOf(Team::class, $team);
    }

    public function testGetUsers()
    {
        $this->mockResponse(200, [], [
            'ok' => true,
            'members' => [
                [
                    'id' => $this->faker->randomAscii,
                ],
            ],
        ]);

        $users = $this->client->getUsers();
        $this->assertLastRequestUrl(ApiClient::BASE_URL.'users.list');
        $this->assertInternalType('array', $users);
        $this->assertCount(1, $users);
        $this->assertInstanceOf(\Slack\User::class, $users[0]);
    }

    public function testApiCall()
    {
        // add the mock subscriber to the client
        $this->mockResponse(200, [], '{"ok": true}');

        // send a request
        $response = $this->client->apiCall('api.test');

        // exactly one request should have been sent
        $this->assertCount(1, $this->history);

        // verify the sent URL
        $this->assertLastRequestUrl(ApiClient::BASE_URL.'api.test');

        // verify response
        $this->assertInstanceOf(Response::class, $response);
    }
}
