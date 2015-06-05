<?php
namespace Slack;

use Devristo\Phpws\Client\WebSocket;
use Devristo\Phpws\Messaging\WebSocketMessageInterface;
use Evenement\EventEmitterTrait;
use React\Promise;

/**
 * A client for the Slack real-time messaging API.
 */
class RealTimeClient extends ApiClient
{
    use EventEmitterTrait;

    /**
     * @var WebSocket A websocket connection to the Slack API.
     */
    protected $websocket;

    /**
     * @var int The ID of the last payload sent to Slack.
     */
    protected $lastMessageId = 0;

    /**
     * @var array An array of pending messages waiting for successful confirmation
     *            from Slack.
     */
    protected $pendingMessages = [];

    /**
     * @var bool Indicates if the client is connected.
     */
    protected $connected = false;

    /**
     * @var Team The team logged in to.
     */
    protected $team;

    /**
     * @var array A map of users.
     */
    protected $users = [];

    /**
     * @var array A map of channels.
     */
    protected $channels = [];

    /**
     * @var array A map of groups.
     */
    protected $groups = [];

    /**
     * @var array A map of direct message channels.
     */
    protected $dms = [];

    /**
     * Connects to the real-time messaging server.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function connect()
    {
        $deferred = new Promise\Deferred();

        // Request a real-time connection...
        $this->apiCall('rtm.start')

        // then connect to the socket...
        ->then(function (Response $response) {
            $responseData = $response->getData();
            // get the team info
            $this->team = new Team($this, $responseData['team']);

            // populate list of users
            foreach ($responseData['users'] as $data) {
                $this->users[$data['id']] = new User($this, $data);
            }

            // populate list of channels
            foreach ($responseData['channels'] as $data) {
                $this->channels[$data['id']] = new Channel($this, $data);
            }

            // populate list of groups
            foreach ($responseData['groups'] as $data) {
                $this->groups[$data['id']] = new Group($this, $data);
            }

            // populate list of dms
            foreach ($responseData['ims'] as $data) {
                $this->dms[$data['id']] = new DirectMessageChannel($this, $data);
            }

            $logger = new \Zend\Log\Logger();
            $writer = new \Zend\Log\Writer\Stream('php://stdout');
            $logger->addWriter($writer);

            // initiate the websocket connection
            $this->websocket = new WebSocket($responseData['url'], $this->loop, $logger);
            $this->websocket->on('message', function ($message) {
                $this->onMessage($message);
            });

            return $this->websocket->open();
        })

        // then wait for the connection to be ready.
        ->then(function () use ($deferred) {
            $this->once('hello', function () use ($deferred) {
                $deferred->resolve();
            });

            $this->once('error', function ($data) use ($deferred) {
                $deferred->reject(new ConnectionException(
                    'Could not connect to WebSocket: '.$data['error']['msg'],
                    $data['error']['code']));
            });
        });

        return $deferred->promise();
    }

    /**
     * Disconnects the client.
     */
    public function disconnect()
    {
        if (!$this->connected) {
            throw new ConnectionException('Client not connected.');
        }

        $this->websocket->close();
        $this->connected = false;
    }

    /**
     * Sends a message.
     *
     * @param string            $text    The message text.
     * @param PostableInterface $channel The channel to send the message to.
     */
    public function send($text, PostableInterface $channel)
    {
        if (!$this->connected) {
            throw new ConnectionException('Client not connected.');
        }

        $data = [
            'id' => ++$this->lastMessageId,
            'type' => 'message',
            'channel' => $channel->getId(),
            'text' => $text,
        ];
        $this->websocket->send(json_encode($data));

        // add message to pending list
        $this->pendingMessages[$this->lastMessageId] = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getTeam()
    {
        return Promise\resolve($this->team);
    }

    /**
     * {@inheritDoc}
     */
    public function getChannelById($id)
    {
        if ($id[0] === 'G') {
            return $this->getGroupById($id);
        } elseif ($id[0] === 'D') {
            return $this->getDMById($id);
        }

        return $this->channels[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function getGroupById($id)
    {
        return Promise\resolve($this->groups[$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function getDMById($id)
    {
        return Promise\resolve($this->dms[$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUserById($id)
    {
        return Promise\resolve($this->users[$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers()
    {
        return Promise\resolve(array_values($this->users));
    }

    /**
     * Handles incoming websocket messages, parses them, and emits them as remote events.
     *
     * @param WebSocketMessageInterface $messageRaw A websocket message.
     */
    private function onMessage(WebSocketMessageInterface $message)
    {
        // parse the message and get the event name
        $payload = Payload::fromJson($message->getData());

        // if reply_to is set, then it is a server confirmation for a previously
        // sent message
        if (isset($payload['reply_to'])) {
            // remove message from pending
            unset($this->pendingMessages[$payload['reply_to']]);
            return;
        }

        // not an event
        if (!isset($payload['type'])) {
            return;
        }

        if ($payload['type'] === 'hello') {
            $this->connected = true;
        }

        // emit an event with the attached json
        $this->emit($payload['type'], [$payload]);
    }
}
