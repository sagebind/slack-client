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
     * @return \Slack\Async\Promise<string>
     */
    public function rename($name)
    {
        return $this->client->apiCall('groups.rename', [
            'channel' => $this->getId(),
            'name' => $name,
        ])->then(\Closure::bind(function () use ($name) {
            $this->data['name'] = $name;
            return $name;
        }, $this));
    }

    /**
     * Sets the group's purpose text.
     *
     * @param string $text The new purpose text to set to.
     *
     * @return \Slack\Async\Promise<string>
     */
    public function setPurpose($text)
    {
        return $this->client->apiCall('groups.setPurpose', [
            'channel' => $this->getId(),
            'purpose' => $text,
        ])->then(\Closure::bind(function () use ($text) {
            $this->data['purpose']['value'] = $text;
            return $text;
        }, $this));
    }

    /**
     * Sets the group topic text.
     *
     * @param string $text The new topic text to set to.
     *
     * @return \Slack\Async\Promise<string>
     */
    public function setTopic($text)
    {
        return $this->client->apiCall('groups.setTopic', [
            'channel' => $this->getId(),
            'topic' => $text,
        ])->then(\Closure::bind(function () use ($text) {
            $this->data['topic']['value'] = $text;
            return $text;
        }, $this));
    }

    /**
     * Archives the group.
     *
     * @return \Slack\Async\Promise
     */
    public function archive()
    {
        return $this->client->apiCall('groups.archive', [
            'channel' => $this->getId(),
        ])->then(\Closure::bind(function () {
            $this->data['is_archived'] = true;
        }, $this));
    }

    /**
     * Un-archives the group.
     *
     * @return \Slack\Async\Promise
     */
    public function unarchive()
    {
        return $this->client->apiCall('groups.unarchive', [
            'channel' => $this->getId(),
        ])->then(\Closure::bind(function () {
            $this->data['is_archived'] = false;
        }, $this));
    }

    /**
     * Invites a user to the group.
     *
     * @param User The user to invite.
     *
     * @return \Slack\Async\Promise
     */
    public function inviteUser(User $user)
    {
        return $this->client->apiCall('groups.invite', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(\Closure::bind(function () use ($user) {
            $this->data['members'][] = $user->getId();
        }, $this));
    }

    /**
     * Kicks a user from the group.
     *
     * @param User The user to kick.
     *
     * @return \Slack\Async\Promise
     */
    public function kickUser(User $user)
    {
        return $this->client->apiCall('groups.kick', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ])->then(\Closure::bind(function () use ($user) {
            unset($this->data['members'][$user->getId()]);
        }, $this));
    }
}
