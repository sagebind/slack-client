<?php
namespace Slack;

/**
 * Interface for room objects that messages can be posted to, such as channels
 * and groups.
 */
interface ChannelInterface
{
    /**
     * Gets the room ID.
     *
     * @return string The room ID.
     */
    public function getId();

    /*
     * Closes the channel.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function close();
}
