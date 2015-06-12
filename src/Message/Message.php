<?php
namespace Slack\Message;

use Slack\ApiClient;
use Slack\ClientObject;

/**
 * Represents a chat message and its data.
 */
class Message extends ClientObject
{
    protected $attachments = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(ApiClient $client, array $data)
    {
        parent::__construct($client, $data);

        // convert raw data into attachment objects we can use later
        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attData) {
                $this->attachments[] = Attachment::fromData($attData);
            }
        }
    }

    /**
     * Gets the message text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->data['text'];
    }

    /**
     * Checks if the message has attachments.
     *
     * @return bool True if the message has attachments, otherwise false.
     */
    public function hasAttachments()
    {
        return count($this->attachments) > 0;
    }

    /**
     * Gets all message attachments.
     *
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Gets the channel the message is in.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getChannel()
    {
        return $this->client->getChannelById($this->data['channel']);
    }

    /**
     * Gets the user that sent the message.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getUser()
    {
        return $this->client->getUserById($this->data['user']);
    }
}
