<?php
namespace Slackyboy\Slack;

/**
 * Represents a single Slack channel.
 */
class Channel extends ClientObject
{
    /**
     * Gets the channel name.
     *
     * @return string The name of the channel.
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * Gets an iterator over all users in the channel.
     *
     * @return Generator A generator that yields user objects for each member in
     *                   the channel.
     */
    public function getMembers()
    {
        foreach ($this->data['members'] as $memberId) {
            yield $this->client->getUserById($memberId);
        }
    }

    /**
     * Gets the time the channel was created.
     *
     * @return \DateTime The time the channel was created.
     */
    public function getTimeCreated()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->data['created']);
        return $time;
    }

    /**
     * Gets the creator of the channel.
     *
     * @return User The user who created the channel.
     */
    public function getCreator()
    {
        return $this->client->getUserById($this->data['creator']);
    }

    /**
     * Gets the number of message unread by the authenticated user.
     *
     * @return int The number of unread messages.
     */
    public function getUnreadCount()
    {
        return $this->data['unread_count'];
    }

    /**
     * Checks if the channel has been archived.
     *
     * @return bool True if the channel has been archived, otherwise false.
     */
    public function isArchived()
    {
        return $this->data['is_archived'];
    }
}
