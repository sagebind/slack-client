<?php
namespace Slackyboy\Slack;

/**
 * An object fetched from the Slack API.
 */
abstract class ClientObject
{
    /**
     * @var ApiClient The API client the object belongs to.
     */
    protected $client;

    /**
     * @var array The object's data cached from the remote server.
     */
    protected $data = [];

    /**
     * Creates a client object from a data array.
     *
     * @param ApiClient The API client the object belongs to.
     * @param array $data An array of model data.
     */
    public function __construct(ApiClient $client, array $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Gets the object ID.
     *
     * @return string The object ID.
     */
    public function getId()
    {
        return $this->data['id'];
    }
}
