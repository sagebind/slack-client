<?php
namespace Slackyboy\Slack;

/**
 * Gets information about a Slack user.
 */
class User extends ClientObject
{
    public function getUsername()
    {
        return $this->getData()['name'];
    }

    protected function fetchData()
    {
        $response = $this->getClient()->sendRequest('users.info', [
            'user' => $this->getId(),
        ]);

        return $response['user'];
    }
}
