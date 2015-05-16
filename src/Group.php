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
     */
    public function rename($name)
    {
        $this->client->apiCall('groups.rename', [
            'channel' => $this->getId(),
            'name' => $name,
        ]);

        $this->data['name'] = $name;
    }

    /**
     * Sets the group's purpose text.
     *
     * @param string $text The new purpose text to set to.
     */
    public function setPurpose($text)
    {
        $this->client->apiCall('groups.setPurpose', [
            'channel' => $this->getId(),
            'purpose' => $text,
        ]);

        $this->data['purpose']['value'] = $text;
    }

    /**
     * Sets the group topic text.
     *
     * @param string $text The new topic text to set to.
     */
    public function setTopic($text)
    {
        $this->client->apiCall('groups.setTopic', [
            'channel' => $this->getId(),
            'topic' => $text,
        ]);

        $this->data['topic']['value'] = $text;
    }

    /**
     * Archives the group.
     */
    public function archive()
    {
        $this->client->apiCall('groups.archive', [
            'channel' => $this->getId(),
        ]);

        $this->data['is_archived'] = true;
    }

    /**
     * Un-archives the group.
     */
    public function unarchive()
    {
        $this->client->apiCall('groups.unarchive', [
            'channel' => $this->getId(),
        ]);

        $this->data['is_archived'] = false;
    }

    /**
     * Invites a user to the group.
     *
     * @param User The user to invite.
     */
    public function inviteUser(User $user)
    {
        $this->client->apiCall('groups.invite', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ]);

        $this->data['members'][] = $user->getId();
    }

    /**
     * Kicks a user from the group.
     *
     * @param User The user to kick.
     */
    public function kickUser(User $user)
    {
        $this->client->apiCall('groups.kick', [
            'channel' => $this->getId(),
            'user' => $user->getId(),
        ]);

        unset($this->data['members'][$user->getId()]);
    }
}
