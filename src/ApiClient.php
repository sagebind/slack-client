<?php
namespace Slack;

use GuzzleHttp;
use Psr\Http\Message\PayloadInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

/**
 * A client for connecting to the Slack Web API and calling remote API methods.
 */
class ApiClient
{
    /**
     * The base URL for API requests.
     */
    const BASE_URL = 'https://slack.com/api/';

    /**
     * @var string The Slack API token string.
     */
    protected $token;

    /**
     * @var GuzzleHttp\Client A Guzzle HTTP client.
     */
    protected $httpClient;

    /**
     * @var LoopInterface An event loop instance.
     */
    protected $loop;

    /**
     * Creates a new API client instance.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient A Guzzle client instance to
     *                                                send requests with.
     */
    public function __construct(LoopInterface $loop, GuzzleHttp\ClientInterface $httpClient = null)
    {
        $this->loop = $loop;
        $this->httpClient = $httpClient ?: new GuzzleHttp\Client();
    }

    /**
     * Sets the Slack API token to be used during method calls.
     *
     * @param string $token The API token string.
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Gets the currently authenticated user.
     *
     * @return \React\Promise\PromiseInterface A promise for the currently authenticated user.
     */
    public function getAuthedUser()
    {
        return $this->apiCall('auth.test')->then(function (Payload $response) {
            return $this->getUserById($response['user_id']);
        });
    }

    /**
     * Gets information about the current Slack team logged in to.
     *
     * @return \React\Promise\PromiseInterface A promise for the current Slack team.
     */
    public function getTeam()
    {
        return $this->apiCall('team.info')->then(function (Payload $response) {
            return new Team($this, $response['team']);
        });
    }

    /**
     * Gets a channel by its ID.
     *
     * @param string $id A channel ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a channel object.
     */
    public function getChannelById($id)
    {
        return $this->apiCall('channels.info', [
            'channel' => $id,
        ])->then(function (Payload $response) {
            return new Channel($this, $response['channel']);
        });
    }

    /**
     * Gets a user by its ID.
     *
     * @param string $id A user ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a user object.
     */
    public function getUserById($id)
    {
        return $this->apiCall('users.info', [
            'user' => $id,
        ])->then(function (Payload $response) {
            return new User($this, $response['user']);
        });
    }

    /**
     * Gets all users in the Slack team.
     *
     * @return \React\Promise\PromiseInterface A promise for an array of users.
     */
    public function getUsers()
    {
        // get the user list
        return $this->apiCall('users.list')->then(function (Payload $response) {
            $users = [];
            foreach ($response['members'] as $member) {
                $users[] = new User($this, $member);
            }
            return $users;
        });
    }

    /**
     * Sends an API request.
     *
     * @param string $method The API method to call.
     * @param array  $args   An associative array of arguments to pass to the
     *                       method call.
     *
     * @return \React\Promise\PromiseInterface A promise for an API response.
     */
    public function apiCall($method, array $args = [])
    {
        // create the request url
        $requestUrl = self::BASE_URL.$method;

        // set the api token
        $args['token'] = $this->token;

        // send a post request with all arguments
        $promise = $this->httpClient->postAsync($requestUrl, [
            'form_params' => $args,
        ]);

        // Add requests to the event loop to be handled at a later date.
        $this->loop->futureTick(function () use ($promise) {
            $promise->wait();
        });

        // When the response has arrived, parse it and resolve. Note that our
        // promises aren't pretty; Guzzle promises are not compatible with React
        // promises, so the only Guzzle promises ever used die in here and it is
        // React from here on out.
        $deferred = new Deferred();
        $promise->then(function (PayloadInterface $response) use ($deferred) {
            // get the response as a json object
            $payload = Payload::fromJson((string)$response->getBody());

            // check if there was an error
            if (!isset($payload['okay']) || !$payload['okay']) {
                // make a nice-looking error message and throw an exception
                $niceMessage = ucfirst(str_replace('_', ' ', $payload['error']));
                $deferred->reject(new ApiException($niceMessage));
            } else {
                $deferred->resolve($payload);
            }
        });

        return $deferred->promise();
    }
}
