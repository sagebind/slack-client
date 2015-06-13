<?php
namespace Slack\Tests;

use Slack\ApiClient;
use Slack\Payload;
use Slack\Team;

class ApiClientTest extends ClientTestCase
{
    public function testGetTeam()
    {
        $this->mockResponse(200, [], [
            'ok' => true,
            'team' => [
                'id' => $this->faker->randomAscii,
            ],
        ]);

        $team = $this->client->getTeam()->then(function (Team $team) {
            $this->assertLastRequestUrl(ApiClient::BASE_URL.'team.info');
        });

        $this->watchPromise($team);
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

        $users = $this->client->getUsers()->then(function ($users) {
            $this->assertLastRequestUrl(ApiClient::BASE_URL.'users.list');
            $this->assertInternalType('array', $users);
            $this->assertCount(1, $users);
            $this->assertInstanceOf(\Slack\User::class, $users[0]);
        });

        $this->watchPromise($users);
    }

    public function testApiCall()
    {
        // add the mock subscriber to the client
        $this->mockResponse(200, [], [
            'ok' => true,
        ]);

        // send a request
        $response = $this->client->apiCall('api.test')->then(function (Payload $response) {
            // exactly one request should have been sent
            $this->assertCount(1, $this->history);

            // verify the sent URL
            $this->assertLastRequestUrl(ApiClient::BASE_URL.'api.test');
        });

        $this->watchPromise($response);
    }
}
