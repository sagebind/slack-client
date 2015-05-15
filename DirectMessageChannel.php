<?php
namespace Slackyboy\Slack;

/**
 * Contains information about a direct message channel.
 */
class DirectMessageChannel extends Channel
{
    /**
     * {@inheritDoc}
     */
    public function getMembers()
    {
        yield $this->client->getUserById($this->data['user']);
    }
}
