<?php
namespace Slack\Tests;

use Slack\ApiClient;
use Slack\Payload;
use Slack\Team;
use Slack\User;

class ApiClientTest extends TestCase
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
            $this->assertInstanceOf(User::class, $users[0]);
        });

        $this->watchPromise($users);
    }

    public function testGetUserById()
    {
        $id = $this->faker->uuid;

        $this->mockResponse(200, null, [
            'ok' => true,
            'user' => [
                'id' => $id,
            ],
        ]);

        $users = $this->client->getUserById($id)->then(function (User $user) use ($id) {
            $this->assertEquals($id, $user->getId());
        });

        $this->watchPromise($users);
    }

    public function testGetUserByName()
    {
        $id = $this->faker->uuid;
        $username = $this->faker->userName;

        $this->mockResponse(200, [], [
            'ok' => true,
            'members' => [
                [
                    'id' => $this->faker->uuid,
                    'name' => $this->faker->userName,
                ],
                [
                    'id' => $id,
                    'name' => $username,
                ],
                [
                    'id' => $this->faker->uuid,
                    'name' => $this->faker->userName,
                ],
            ],
        ]);

        $users = $this->client->getUserByName($username)->then(function (User $user) use ($id) {
            $this->assertEquals($id, $user->getId());
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
