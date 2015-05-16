<?php
namespace Slack;

/**
 * Interface for channel objects that messages can be posted to.
 */
interface PostableInterface
{
    /**
     * Gets the channel ID.
     *
     * @return string The channel ID.
     */
    public function getId();
}
