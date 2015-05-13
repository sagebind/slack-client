<?php
namespace Slackyboy\Slack;

class Message
{
    protected $text;
    protected $channel;
    protected $user;

    public function __construct($text, $channel)
    {
        $this->text = $text;
        $this->channel = $channel;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getChannel()
    {
        return $this->channel;
    }
}
