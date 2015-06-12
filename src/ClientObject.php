<?php
namespace Slack;

/**
 * An object fetched from the Slack API.
 */
abstract class ClientObject extends DataObject
{
    /**
     * @var ApiClient The API client the object belongs to.
     */
    protected $client;

    /**
     * Creates a client object from a data array.
     *
     * @param ApiClient $client The API client the object belongs to.
     * @param array     $data   An array of model data.
     */
    public function __construct(ApiClient $client, array $data)
    {
        $this->client = $client;
        $this->data = $data;
    }

    /**
     * Gets the client object that created the object.
     *
     * @return ApiClient The client object that created the object.
     */
    public function getClient()
    {
        return $this->client;
    }
}
