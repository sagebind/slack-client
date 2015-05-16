<?php
namespace Slack;

/**
 * Represents a single Slack channel.
 */
class Channel extends ClientObject implements PostableInterface
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
     * Gets the channel's purpose text.
     *
     * @return string The channel's purpose text.
     */
    public function getPurpose()
    {
        return $this->data['purpose']['value'];
    }

    /**
     * Gets the channel topic text.
     *
     * @return string The channel's topic text.
     */
    public function getTopic()
    {
        return $this->data['topic']['value'];
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

    /**
     * Renames the channel.
     *
     * @param string $name The name to set to.
     */
    public function rename($name)
    {
        $this->client->apiCall('channels.rename', [
            'channel' => $this->getId(),
            'name' => $name,
        ]);

        $this->data['name'] = $name;
    }

    /**
     * Sets the channel's purpose text.
     *
     * @param string $text The new purpose text to set to.
     */
    public function setPurpose($text)
    {
        $this->client->apiCall('channels.setPurpose', [
            'channel' => $this->getId(),
            'purpose' => $text,
        ]);

        $this->data['purpose']['value'] = $text;
    }

    /**
     * Sets the channel topic text.
     *
     * @param string $text The new topic text to set to.
     */
    public function setTopic($text)
    {
        $this->client->apiCall('channels.setTopic', [
            'channel' => $this->getId(),
            'topic' => $text,
        ]);

        $this->data['topic']['value'] = $text;
    }

    /**
     * Archives the channel.
     */
    public function archive()
    {
        $this->client->apiCall('channels.archive', [
            'channel' => $this->getId(),
        ]);

        $this->data['is_archived'] = true;
    }

    /**
     * Un-archives the channel.
     */
    public function unarchive()
    {
        $this->client->apiCall('channels.unarchive', [
            'channel' => $this->getId(),
        ]);

        $this->data['is_archived'] = false;
    }

    /**
     * Invites a user to the channel.
     *
     * @param User The user to invite.
     */
    public function inviteUser(User $user)
    {
        $this->client->apiCall('channels.invite', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ]);

        $this->data['members'][] = $user->getId();
    }

    /**
     * Kicks a user from the channel.
     *
     * @param User The user to kick.
     */
    public function kickUser(User $user)
    {
        $this->client->apiCall('channels.kick', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ]);

        unset($this->data['members'][$user->getId()]);
    }
}
