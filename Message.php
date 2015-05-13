<?php
namespace Slackyboy\Slack;

class Message extends ClientObject
{
    public function getText()
    {
        return $this->getData()['text'];
    }

    public function getUser()
    {
        return User::fromId($this->getClient(), $this->getData()['user']);
    }

    public function getChannel()
    {
        return Channel::fromId($this->getClient(), $this->getData()['channel']);
    }
}
