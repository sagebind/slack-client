<?php
namespace Slack\Tests\Message;

use Slack\ChannelInterface;
use Slack\Message\Attachment;
use Slack\Message\Message;
use Slack\Tests\TestCase;
use Slack\User;

class MessageTest extends TestCase
{
    public function testGetText()
    {
        $text = $this->faker->sentence;

        $message = new Message($this->client, [
            'text' => $text,
        ]);

        $this->assertEquals($text, $message->getText());
    }

    public function testHasAttachmentsIsFalseWhenEmpty()
    {
        $attachment = new Attachment($this->faker->title, $this->faker->sentence);
        $message = new Message($this->client, []);
        $this->assertFalse($message->hasAttachments());
    }

    public function testHasAttachmentsIsTrueWhenAttachments()
    {
        $message = new Message($this->client, [
            'attachments' => [
                new Attachment($this->faker->title, $this->faker->sentence),
            ],
        ]);
        $this->assertTrue($message->hasAttachments());
    }

    public function testGetAttachments()
    {
        $message = new Message($this->client, [
            'attachments' => [],
        ]);

        $count = rand(1, 10);
        foreach (range(1, $count) as $i) {
            $message->data['attachments'][] = new Attachment($this->faker->title, $this->faker->sentence);
        }

        $this->assertCount($count, $message->getAttachments());
        $this->assertEquals($message->data['attachments'], $message->getAttachments());
    }

    public function testGetChannel()
    {
        $channelId = $this->faker->uuid;
        $message = new Message($this->client, [
            'id' => $this->faker->uuid,
            'channel' => $channelId,
        ]);

        $this->mockResponse(200, null, [
            'ok' => true,
            'channel' => [
                'id' => $channelId,
            ],
        ]);

        $this->watchPromise($message->getChannel()->then(function (ChannelInterface $channel) use ($channelId) {
            $this->assertEquals($channelId, $channel->getId());
        }));
    }

    public function testGetUser()
    {
        $userId = $this->faker->uuid;
        $message = new Message($this->client, [
            'id' => $this->faker->uuid,
            'user' => $userId,
        ]);

        $this->mockResponse(200, null, [
            'ok' => true,
            'user' => [
                'id' => $userId,
            ],
        ]);

        $this->watchPromise($message->getUser()->then(function (User $user) use ($userId) {
            $this->assertEquals($userId, $user->getId());
        }));
    }
}
