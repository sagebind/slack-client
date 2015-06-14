<?php
namespace Slack\Tests;

use Slack\Channel;
use Slack\User;

class ChannelTest extends TestCase
{
    public function testGetId()
    {
        $id = $this->faker->uuid;
        $channel = new Channel($this->client, [
            'id' => $id,
        ]);

        $this->assertEquals($id, $channel->getId());
    }

    public function testGetName()
    {
        $name = $this->faker->title;
        $channel = new Channel($this->client, [
            'name' => $name,
        ]);

        $this->assertEquals($name, $channel->getName());
    }

    public function testGetPurpose()
    {
        $purpose = $this->faker->sentence;
        $channel = new Channel($this->client, [
            'purpose' => [
                'value' => $purpose,
            ],
        ]);

        $this->assertEquals($purpose, $channel->getPurpose());
    }

    public function testGetTopic()
    {
        $topic = $this->faker->sentence;
        $channel = new Channel($this->client, [
            'topic' => [
                'value' => $topic,
            ],
        ]);

        $this->assertEquals($topic, $channel->getTopic());
    }

    public function testGetTimeCreated()
    {
        $time = $this->faker->dateTime;
        $channel = new Channel($this->client, [
            'created' => $time->getTimestamp(),
        ]);

        $this->assertEquals($time, $channel->getTimeCreated());
    }

    public function testGetUnreadCount()
    {
        $count = $this->faker->randomDigit;
        $channel = new Channel($this->client, [
            'unread_count' => $count,
        ]);

        $this->assertEquals($count, $channel->getUnreadCount());
    }

    public function testIsArchived()
    {
        $is = $this->faker->boolean;
        $channel = new Channel($this->client, [
            'is_archived' => $is,
        ]);

        $this->assertEquals($is, $channel->isArchived());
    }

    public function testGetCreator()
    {
        $creator = new User($this->client, [
            'id' => $this->faker->uuid,
        ]);

        $channel = new Channel($this->client, [
            'creator' => $creator->getId(),
        ]);

        $this->mockResponse(200, null, [
            'ok' => true,
            'user' => [
                'id' => $creator->data['id'],
            ],
        ]);

        $this->watchPromise($channel->getCreator()->then(function (User $user) use ($creator) {
            $this->assertEquals($creator->data, $user->data);
        }));
    }
}
