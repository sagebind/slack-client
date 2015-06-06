<?php
namespace Slack;

use React\Promise;

/**
 * Represents a single Slack channel.
 */
class Channel extends ClientObject implements ChannelInterface
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->data['id'];
    }

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
     * @return \React\Promise\PromiseInterface A promise for an array of user
     *                                         objects for each member in the channel.
     */
    public function getMembers()
    {
        $memberPromises = [];
        foreach ($this->data['members'] as $memberId) {
            $memberPromises[] = $this->client->getUserById($memberId);
        }

        return Promise\all($memberPromises);
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
     * @return \React\Promise\PromiseInterface The user who created the channel.
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
     *
     * @return \React\Promise\PromiseInterface
     */
    public function rename($name)
    {
        return $this->client->apiCall('channels.rename', [
            'channel' => $this->getId(),
            'name' => $name,
        ])->then(function () use ($name) {
            $this->data['name'] = $name;
            return $name;
        });
    }

    /**
     * Sets the channel's purpose text.
     *
     * @param string $text The new purpose text to set to.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function setPurpose($text)
    {
        return $this->client->apiCall('channels.setPurpose', [
            'channel' => $this->getId(),
            'purpose' => $text,
        ])->then(function () use ($text) {
            $this->data['purpose']['value'] = $text;
            return $text;
        });
    }

    /**
     * Sets the channel topic text.
     *
     * @param string $text The new topic text to set to.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function setTopic($text)
    {
        return $this->client->apiCall('channels.setTopic', [
            'channel' => $this->getId(),
            'topic' => $text,
        ])->then(function () use ($text) {
            $this->data['topic']['value'] = $text;
            return $text;
        });
    }

    /**
     * Archives the channel.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function archive()
    {
        return $this->client->apiCall('channels.archive', [
            'channel' => $this->getId(),
        ])->then(function () {
            $this->data['is_archived'] = true;
        });
    }

    /**
     * Un-archives the channel.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function unarchive()
    {
        return $this->client->apiCall('channels.unarchive', [
            'channel' => $this->getId(),
        ])->then(function () {
            $this->data['is_archived'] = false;
        });
    }

    /**
     * Invites a user to the channel.
     *
     * @param User The user to invite.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function inviteUser(User $user)
    {
        return $this->client->apiCall('channels.invite', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(function () use ($user) {
            $this->data['members'][] = $user->getId();
        });
    }

    /**
     * Kicks a user from the channel.
     *
     * @param User The user to kick.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function kickUser(User $user)
    {
        return $this->client->apiCall('channels.kick', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(function () use ($user) {
            unset($this->data['members'][$user->getId()]);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return $this->client->apiCall('channels.close', [
            'channel' => $this->getId(),
        ])->then(function ($response) {
            return !isset($response['no_op']);
        });
    }
}
