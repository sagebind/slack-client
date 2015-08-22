<?php
namespace Slack;

/**
 * Contains information about a private group channel.
 */
class Group extends Channel
{
    /**
     * Renames the group.
     *
     * @param string $name The name to set to.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function rename($name)
    {
        return $this->client->apiCall('groups.rename', [
            'channel' => $this->getId(),
            'name' => $name,
        ])->then(function () use ($name) {
            $this->data['name'] = $name;
            return $name;
        });
    }

    /**
     * Sets the group's purpose text.
     *
     * @param string $text The new purpose text to set to.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function setPurpose($text)
    {
        return $this->client->apiCall('groups.setPurpose', [
            'channel' => $this->getId(),
            'purpose' => $text,
        ])->then(function () use ($text) {
            $this->data['purpose']['value'] = $text;
            return $text;
        });
    }

    /**
     * Sets the group topic text.
     *
     * @param string $text The new topic text to set to.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function setTopic($text)
    {
        return $this->client->apiCall('groups.setTopic', [
            'channel' => $this->getId(),
            'topic' => $text,
        ])->then(function () use ($text) {
            $this->data['topic']['value'] = $text;
            return $text;
        });
    }

    /**
     * Archives the group.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function archive()
    {
        return $this->client->apiCall('groups.archive', [
            'channel' => $this->getId(),
        ])->then(function () {
            $this->data['is_archived'] = true;
        });
    }

    /**
     * Un-archives the group.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function unarchive()
    {
        return $this->client->apiCall('groups.unarchive', [
            'channel' => $this->getId(),
        ])->then(function () {
            $this->data['is_archived'] = false;
        });
    }

    /**
     * Invites a user to the group.
     *
     * @param User $user The user to invite.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function inviteUser(User $user)
    {
        return $this->client->apiCall('groups.invite', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(function () use ($user) {
            $this->data['members'][] = $user->getId();
        });
    }

    /**
     * Kicks a user from the group.
     *
     * @param User $user The user to kick.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function kickUser(User $user)
    {
        return $this->client->apiCall('groups.kick', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(function () use ($user) {
            unset($this->data['members'][$user->getId()]);
        });
    }

    /**
     * Opens the group.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function open()
    {
        return $this->client->apiCall('groups.open', [
            'channel' => $this->getId(),
        ])->then(function ($response) {
            return !isset($response['no_op']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return $this->client->apiCall('groups.close', [
            'channel' => $this->getId(),
        ])->then(function ($response) {
            return !isset($response['no_op']);
        });
    }
}
