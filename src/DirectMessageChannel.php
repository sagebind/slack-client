<?php
namespace Slack;

/**
 * Contains information about a direct message channel.
 */
class DirectMessageChannel extends ClientObject implements ChannelInterface
{
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Gets the time the channel was created.
     *
     * @return \DateTime The time the channel was created.
     */
    public function getTimeCreated()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->data['created']);
        return $time;
    }

    /**
     * Gets the user the direct message channel is with.
     *
     * @return \React\Promise\PromiseInterface
     */
    public function getUser()
    {
        return $this->client->getUserById($this->data['user']);
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return $this->client->apiCall('im.close', [
            'channel' => $this->getId(),
        ])->then(function ($response) {
            return !isset($response['no_op']);
        });
    }
}
