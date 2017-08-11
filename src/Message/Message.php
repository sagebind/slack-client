<?php
namespace Slack\Message;

use Slack\ClientObject;

/**
 * Represents a chat message and its data.
 */
class Message extends ClientObject
{
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
     * Checks if Markdown is enabled for the message text.
     *
     * @return bool
     */
    public function isMarkdownEnabled()
    {
        return isset($this->data['mrkdwn']) ? $this->data['mrkdwn'] == true : true;
    }

    /**
     * Checks if the message has attachments.
     *
     * @return bool True if the message has attachments, otherwise false.
     */
    public function hasAttachments()
    {
        return isset($this->data['attachments']) && count($this->data['attachments']) > 0;
    }

    /**
     * Gets all message attachments.
     *
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return isset($this->data['attachments']) ? $this->data['attachments'] : [];
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

    /**
     * Send message as user or not.
     * By default sends as user.
     *
     * @return bool
     */
    public function isAsUser()
    {
        return isset($this->data['as_user']) ? $this->data['as_user'] : true;
    }

    /**
     * Get message's user display name
     *
     * @return string
     */
    public function getUsername()
    {
        return isset($this->data['username']) ? $this->data['username'] : '';
    }

    /**
     * {@inheritDoc}
     */
    public function jsonUnserialize(array $data)
    {
        if (!isset($this->data['attachments'])) {
            return;
        }

        for ($i = 0; $i < count($this->data['attachments']); $i++) {
            $this->data['attachments'][$i] = Attachment::fromData($this->data['attachments'][$i]);
        }
    }
}
