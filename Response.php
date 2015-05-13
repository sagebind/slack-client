<?php
namespace Slackyboy\Slack;

/**
 * Represents a response from a Slack API call.
 */
class Response
{
    protected $data;

    public static function fromJson($json)
    {
        $response = new static();
    }

    public function isOkay()
    {
        return isset($this->data['ok']) && $this->data['ok'] === true;
    }

    /**
     * Gets the data associated with the response.
     *
     * @return array
     */
    public function getData()
    {
    }
}
