<?php
namespace Slackyboy\Slack;

use GuzzleHttp;

/**
 * A client for connecting to the Slack Web API and calling remote API methods.
 */
class ApiClient
{
    /**
     * @var string The Slack API token string.
     */
    protected $token;

    /**
     * @var GuzzleHttp\Client A Guzzle HTTP client.
     */
    protected $httpClient;

    /**
     * Creates a new API client instance.
     */
    public function __construct()
    {
        $this->httpClient = new GuzzleHttp\Client();
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
     * @return User The currently authenticated user.
     */
    public function getAuthedUser()
    {
        $response = $this->apiCall('auth.test');
        return $this->getUserById($response['user_id']);
    }

    /**
     * Gets a channel by its ID.
     *
     * @param string $id A channel ID.
     *
     * @return Channel A channel object.
     */
    public function getChannelById($id)
    {
        $response = $this->apiCall('channels.info', [
            'channel' => $id,
        ]);

        return new Channel($this, $response['channel']);
    }

    /**
     * Gets a user by its ID.
     *
     * @param string $id A user ID.
     *
     * @return User A user object.
     */
    public function getUserById($id)
    {
        $response = $this->apiCall('users.info', [
            'user' => $id,
        ]);

        return new User($this, $response['user']);
    }

    /**
     * Gets all users in the Slack team.
     *
     * @return User[] An array of users.
     */
    public function getUsers()
    {
        // get the user list
        $response = $this->apiCall('users.list');

        $users = [];
        foreach ($response['members'] as $member) {
            $users[] = new User($this, $member);
        }

        return $users;
    }

    /**
     * Sends an API request.
     *
     * @param string $method The API method to call.
     * @param array  $args   An associative array of arguments to pass to the
     *                       method call.
     *
     * @return Response The API call response.
     */
    protected function apiCall($method, array $args = [])
    {
        // create the request url
        $requestUrl = 'https://slack.com/api/'.$method;

        // set the api token
        $args['token'] = $this->token;

        // send a post request with all arguments
        $response = $this->httpClient->post($requestUrl, [
            'body' => $args,
        ]);

        // get the response as a json object
        $json = $response->json();

        // check if there was an error
        if (!$json['ok']) {
            // make a nice-looking error message and throw an exception
            $niceMessage = ucfirst(str_replace('_', ' ', $json['error']));
            throw new ApiException($niceMessage);
        }

        return $json;
    }
}
