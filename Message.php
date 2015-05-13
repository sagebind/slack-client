<?php
namespace Slackyboy\Slack;

class Message extends ClientObject
{
    public function getText()
    {
        if (isset($this->getData()['text'])) {
            return $this->getData()['text'];
        }

        return '';
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
