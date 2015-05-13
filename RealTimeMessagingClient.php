<?php
namespace Slackyboy\Slack;

use Devristo\Phpws\Client\WebSocket;
use Evenement\EventEmitterTrait;
use React\EventLoop;

/**
 * A client for the Slack real-time messaging API.
 */
class RealTimeMessagingClient
{
    use EventEmitterTrait;

    protected $websocket;
    protected $websocketUrl;
    protected $client;
    protected $lastMessageId = 0;
    protected $listening = false;

    protected $loop;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
        $this->loop = EventLoop\Factory::create();
    }

    /**
     * Connects to the real-time messaging server.
     *
     * @return [type] [description]
     */
    public function connect()
    {
        $response = $this->client->sendRequest('rtm.start');

        if ($response['ok']) {
            $this->websocketUrl = $response['url'];

            $logger = new \Zend\Log\Logger();
            $writer = new \Zend\Log\Writer\Stream('php://stdout');
            $logger->addWriter($writer);

            // initiate the websocket connection
            $this->websocket = new WebSocket($response['url'], $this->loop, $logger);
        }
    }

    public function listen()
    {
        $this->listening = true;

        $this->websocket->on('message', function ($messageRaw) {
            // parse the message and get the event name
            $message = json_decode($messageRaw->getData(), true);

            // not an event
            if (!isset($message['type'])) {
                return;
            }

            $eventName = str_replace('_', '.', $message['type']);

            // emit an event with the attached json
            $this->emit($eventName, [$message]);
        });

        $this->websocket->open();
        $this->loop->run();
    }

    public function send($text, Channel $channel)
    {
        $data = [
            'id' => ++$this->lastMessageId,
            'type' => 'message',
            'channel' => $channel->getId(),
            'text' => $text,
        ];
        $this->websocket->send(json_encode($data));
    }

    public function disconnect()
    {
        $this->websocket->close();
        $this->listening = false;
    }
}
