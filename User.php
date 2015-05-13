<?php
namespace Slackyboy\Slack;

/**
 * Gets information about a Slack user.
 */
class User extends AbstractModel
{
    /**
     * Creates a new user object from a user ID.
     *
     * @param ApiClient $client [description]
     * @param [type]    $userId [description]
     */
    public function __construct(ApiClient $client, $userId)
    {
        $this->client = $client;
        $this->data['id'] = $userId;
    }

    /**
     * Gets the user's user ID.
     *
     * @return string A user ID.
     */
    public function getId()
    {
        return $this->data['id'];
    }

    public function getUsername()
    {
        return $this->getData()['name'];
    }

    protected function fetchData()
    {
        $response = $this->client->sendRequest('users.info', [
            'user' => $this->getId(),
        ]);

        return $response['user'];
    }
}
