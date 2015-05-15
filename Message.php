<?php
namespace Slackyboy\Slack;

class Message extends ClientObject
{
    public function getText()
    {
        return $this->data['text'];
    }

    public function getUser()
    {
        return $this->client->getUserById($this->data['user']);
    }

    public function getChannel()
    {
        return $this->client->getChannelById($this->data['channel']);
    }
}
