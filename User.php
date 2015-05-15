<?php
namespace Slackyboy\Slack;

/**
 * Gets information about a Slack user.
 */
class User extends ClientObject
{
    public function getUsername()
    {
        return $this->data['name'];
    }
}
