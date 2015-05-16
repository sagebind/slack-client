<?php
namespace Slack;

/**
 * Contains information about a direct message channel.
 */
class DirectMessageChannel extends ClientObject implements PostableInterface
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->data['id'];
    }
}
