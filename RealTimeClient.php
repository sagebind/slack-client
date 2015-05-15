<?php
namespace Slackyboy\Slack;

use Devristo\Phpws\Client\WebSocket;
use Devristo\Phpws\Messaging\WebSocketMessageInterface;
use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;

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
    protected $pendingMessages;

    /**
     * @var LoopInterface An event loop instance.
     */
    protected $loop;

    /**
     * @var bool Indicates if the client is connected.
     */
    protected $connected = false;

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
     * Creates a new real-time Slack client.
     */
    public function __construct(LoopInterface $loop)
    {
        parent::__construct();
        $this->loop = $loop;
        $this->pendingMessages = new \SplObjectStorage();
    }

    /**
     * Connects to the real-time messaging server.
     */
    public function connect()
    {
        // connect
        $response = $this->apiCall('rtm.start');

        // populate list of users
        foreach ($response['users'] as $data) {
            $this->users[$data['id']] = new User($this, $data);
        }

        // populate list of channels
        foreach ($response['channels'] as $data) {
            $this->channels[$data['id']] = new Channel($this, $data);
        }

        // populate list of groups
        foreach ($response['groups'] as $data) {
            $this->groups[$data['id']] = new Group($this, $data);
        }

        // populate list of dms
        foreach ($response['ims'] as $data) {
            $this->dms[$data['id']] = new DirectMessageChannel($this, $data);
        }

        $logger = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream('php://stdout');
        $logger->addWriter($writer);

        // initiate the websocket connection
        $this->websocket = new WebSocket($response['url'], $this->loop, $logger);
        $this->websocket->on('message', function ($message) {
            $this->onMessage($message);
        });

        $this->websocket->open();
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
     * @param string  $text    The message text.
     * @param Channel $channel The channel to send the message to.
     */
    public function send($text, Channel $channel)
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
        return $this->groups[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function getDMById($id)
    {
        return $this->dms[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function getUserById($id)
    {
        return $this->users[$id];
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers()
    {
        return array_values($this->users);
    }

    /**
     * Handles incoming websocket messages.
     *
     * @param WebSocketMessageInterface $messageRaw A websocket message.
     */
    protected function onMessage(WebSocketMessageInterface $messageRaw)
    {
        // parse the message and get the event name
        $message = json_decode($messageRaw->getData(), true);

        // if reply_to is set, then it is a server confirmation for a previously
        // sent message
        if (isset($message['reply_to'])) {
            // remove message from pending
            unset($this->pendingMessages[$message['reply_to']]);
            return;
        }

        // not an event
        if (!isset($message['type'])) {
            return;
        }

        if ($message['type'] === 'hello') {
            $this->connected = true;
            return;
        }

        // emit an event with the attached json
        $this->emit($message['type'], [$message]);
    }
}
