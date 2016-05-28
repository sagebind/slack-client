<?php
namespace Slack;

/**
 * Contains information about a team member.
 */
class User extends ClientObject
{
    /**
     * Gets the user's ID.
     *
     * @return string The user's ID.
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Gets the user's username.
     *
     * Does not include the @ symbol at the beginning.
     *
     * @return string The user's username.
     */
    public function getUsername()
    {
        return $this->data['name'];
    }

    /**
     * Gets the user's first name if supplied.
     *
     * @return string The user's first name, or null if no name was given.
     */
    public function getFirstName()
    {
        return isset($this->data['profile']['first_name']) ? $this->data['profile']['first_name'] : null;
    }

    /**
     * Gets the user's last name if supplied.
     *
     * @return string The user's last name, or null if no name was given.
     */
    public function getLastName()
    {
        return isset($this->data['profile']['last_name']) ? $this->data['profile']['last_name'] : null;
    }

    /**
     * Gets the user's real name if supplied.
     *
     * @return string The user's real name, or null if no name was given.
     */
    public function getRealName()
    {
        return isset($this->data['profile']['real_name']) ? $this->data['profile']['real_name'] : null;
    }

    /**
     * Gets the user's email address if supplied.
     *
     * @return string The user's email address, or null if no email was given.
     */
    public function getEmail()
    {
        return isset($this->data['profile']['email']) ? $this->data['profile']['email'] : null;
    }

    /**
     * Gets the user's phone number if supplied.
     *
     * @return string The user's phone number, or null if no number was given.
     */
    public function getPhone()
    {
        return isset($this->data['profile']['phone']) ? $this->data['profile']['phone'] : null;
    }

    /**
     * Gets the user's Skype name if supplied.
     *
     * @return string The user's Skype name, or null if no Skype name was given.
     */
    public function getSkype()
    {
        return isset($this->data['profile']['skype']) ? $this->data['profile']['skype'] : null;
    }

    /**
     * Checks if the user is a team administrator.
     *
     * @return bool True if the user is a team administrator.
     */
    public function isAdmin()
    {
        return $this->data['is_admin'];
    }

    /**
     * Checks if the user is a team owner.
     *
     * @return bool True if the user is a team owner.
     */
    public function isOwner()
    {
        return $this->data['is_owner'];
    }

    /**
     * Checks if the user is the team's primary owner.
     *
     * @return bool True if the user is the primary team owner.
     */
    public function isPrimaryOwner()
    {
        return $this->data['is_primary_owner'];
    }

    /**
     * Checks if the user has been deactivated.
     *
     * @return bool True if the user is deactivated.
     */
    public function isDeleted()
    {
        return $this->data['deleted'];
    }

    /**
     * Gets a user's presence.
     *
     * @return \React\Promise\PromiseInterface The current user's presence, either "active" or "away".
     */
    public function getPresence()
    {
        return $this->client->apiCall('users.getPresence', [
            'user' => $this->getId(),
        ])->then(function (Payload $response) {
            return $response['presence'];
        });
    }

    /**
     * User profile image URL 24x24px
     *
     * @return string URL of the 24x24px user profile image
     */
    public function getProfileImage24()
    {
        return $this->data['profile']['image_24'];
    }

    /**
     * User profile image URL 32x32px
     *
     * @return string URL of the 32x32px user profile image
     */
    public function getProfileImage32()
    {
        return $this->data['profile']['image_32'];
    }

    /**
     * User profile image URL 48x48px
     *
     * @return string URL of the 48x48px user profile image
     */
    public function getProfileImage48()
    {
        return $this->data['profile']['image_48'];
    }

    /**
     * User profile image URL 72x72px
     *
     * @return string URL of the 72x72px user profile image
     */
    public function getProfileImage72()
    {
        return $this->data['profile']['image_72'];
    }

    /**
     * User profile image URL 192x192px
     *
     * @return string URL of the 192x192px user profile image
     */
    public function getProfileImage192()
    {
        return $this->data['profile']['image_192'];
    }
}
