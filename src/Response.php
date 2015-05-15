<?php
namespace Slack;

/**
 * Represents a response from a Slack API call.
 */
class Response
{
    /**
     * @var array The response data.
     */
    protected $data;

    /**
     * Creates a new response object.
     *
     * @param array $data The response data.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Checks if the response is okay.
     *
     * @return bool True if the response is okay, otherwise false.
     */
    public function isOkay()
    {
        return isset($this->data['ok']) && $this->data['ok'] === true;
    }

    /**
     * Gets the data associated with the response.
     *
     * @return array The response data.
     */
    public function getData()
    {
        return $this->data;
    }
}
