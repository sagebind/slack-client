<?php
namespace Slack;

use GuzzleHttp;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use Slack\Message\Message;
use Slack\Message\MessageBuilder;

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
     * @var GuzzleHttp\ClientInterface A Guzzle HTTP client.
     */
    protected $httpClient;

    /**
     * @var LoopInterface An event loop instance.
     */
    protected $loop;

    /**
     * Creates a new API client instance.
     *
     * @param GuzzleHttp\ClientInterface $httpClient A Guzzle client instance to
     *                                               send requests with.
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
     * Gets a message builder for creating a new message object.
     *
     * @return \Slack\Message\MessageBuilder
     */
    public function getMessageBuilder()
    {
        return new MessageBuilder($this);
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
     * Gets a channel, group, or DM channel by ID.
     *
     * @param string $id The channel ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a channel interface.
     */
    public function getChannelGroupOrDMByID($id)
    {
        if ($id[0] === 'D') {
            return $this->getDMById($id);
        }

        if ($id[0] === 'G') {
            return $this->getGroupById($id);
        }

        return $this->getChannelById($id);
    }

    /**
     * Gets all channels in the team.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getChannels()
    {
        return $this->apiCall('channels.list')->then(function ($response) {
            $channels = [];
            foreach ($response['channels'] as $channel) {
                $channels[] = new Channel($this, $channel);
            }
            return $channels;
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
     * Gets a channel by its name.
     *
     * @param string $name The name of the channel.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getChannelByName($name)
    {
        return $this->getChannels()->then(function (array $channels) use ($name) {
            foreach ($channels as $channel) {
                if ($channel->getName() === $name) {
                    return $channel;
                }
            }

            throw new ApiException('Channel ' . $name . ' not found.');
        });
    }

    /**
     * Gets all groups the authenticated user is a member of.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getGroups()
    {
        return $this->apiCall('groups.list')->then(function ($response) {
            $groups = [];
            foreach ($response['groups'] as $group) {
                $groups[] = new Group($this, $group);
            }
            return $groups;
        });
    }

    /**
     * Gets a group by its ID.
     *
     * @param string $id A group ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a group object.
     */
    public function getGroupById($id)
    {
        return $this->apiCall('groups.info', [
            'channel' => $id,
        ])->then(function (Payload $response) {
            return new Group($this, $response['group']);
        });
    }

    /**
     * Gets a group by its name.
     *
     * @param string $name The name of the group.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getGroupByName($name)
    {
        return $this->getGroups()->then(function (array $groups) use ($name) {
            foreach ($groups as $group) {
                if ($group->getName() === $name) {
                    return $group;
                }
            }

            throw new ApiException('Group ' . $name . ' not found.');
        });
    }

    /**
     * Gets all DMs the authenticated user has.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getDMs()
    {
        return $this->apiCall('im.list')->then(function ($response) {
            $dms = [];
            foreach ($response['ims'] as $dm) {
                $dms[] = new DirectMessageChannel($this, $dm);
            }
            return $dms;
        });
    }

    /**
     * Gets a direct message channel by its ID.
     *
     * @param string $id A DM channel ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a DM object.
     */
    public function getDMById($id)
    {
        return $this->getDMs()->then(function (array $dms) use ($id) {
            foreach ($dms as $dm) {
                if ($dm->getId() === $id) {
                    return $dm;
                }
            }

            throw new ApiException('DM ' . $id . ' not found.');
        });
    }

    /**
     * Gets a direct message channel for a given user.
     *
     * @param User $user The user to get a DM for.
     *
     * @return \React\Promise\PromiseInterface A promise for a DM object.
     */
    public function getDMByUser(User $user)
    {
        return $this->getDMByUserId($user->getId());
    }

    /**
     * Gets a direct message channel by user's ID.
     *
     * @param string $id A user ID.
     *
     * @return \React\Promise\PromiseInterface A promise for a DM object.
     */
    public function getDMByUserId($id)
    {
        return $this->apiCall('im.open', [
            'user' => $id,
        ])->then(function (Payload $response) {
            return $this->getDMById($response['channel']['id']);
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
     * Gets a user by username.
     *
     * If the user could not be found, the returned promise is rejected with a
     * `UserNotFoundException` exception.
     *
     * @return \React\Promise\PromiseInterface A promise for a user object.
     */
    public function getUserByName($username)
    {
        return $this->getUsers()->then(function (array $users) use ($username) {
            foreach ($users as $user) {
                if ($user->getUsername() === $username) {
                    return $user;
                }
            }

            throw new UserNotFoundException("The user \"$username\" does not exist.");
        });
    }

    /**
     * Sends a regular text message to a given channel.
     *
     * @param  string                          $text    The message text.
     * @param  ChannelInterface                $channel The channel to send the message to.
     * @return \React\Promise\PromiseInterface
     */
    public function send($text, ChannelInterface $channel)
    {
        $message = $this->getMessageBuilder()
                        ->setText($text)
                        ->setChannel($channel)
                        ->create();

        return $this->postMessage($message);
    }

    /**
     * Posts a message.
     *
     * @param \Slack\Message\Message $message The message to post.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function postMessage(Message $message)
    {
        $options = [
            'text' => $message->getText(),
            'channel' => $message->data['channel'],
            'as_user' => true,
        ];

        if ($message->hasAttachments()) {
            $options['attachments'] = json_encode($message->getAttachments());
        }

        return $this->apiCall('chat.postMessage', $options);
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
        $requestUrl = self::BASE_URL . $method;

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
        $promise->then(function (ResponseInterface $response) use ($deferred) {
            // get the response as a json object
            $payload = Payload::fromJson((string) $response->getBody());

            // check if there was an error
            if (isset($payload['ok']) && $payload['ok'] === true) {
                $deferred->resolve($payload);
            } else {
                // make a nice-looking error message and throw an exception
                $niceMessage = ucfirst(str_replace('_', ' ', $payload['error']));
                $deferred->reject(new ApiException($niceMessage));
            }
        });

        return $deferred->promise();
    }
}
