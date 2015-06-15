<?php
namespace Slack\Tests\Message;

use Slack\Channel;
use Slack\Message\Attachment;
use Slack\Message\Message;
use Slack\Message\MessageBuilder;
use Slack\Tests\TestCase;
use Slack\User;

class MessageBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new MessageBuilder($this->client);
    }

    public function testCreateReturnsMessage()
    {
        $message = $this->builder->create();
        $this->assertInstanceOf(Message::class, $message);
    }

    public function testSetText()
    {
        $message = $this->builder->setText('text')->create();
        $this->assertEquals('text', $message->getText());
    }

    public function testSetChannel()
    {
        $channel = new Channel($this->client, ['id' => 'C1234']);
        $message = $this->builder->setChannel($channel)->create();

        $this->mockResponse(200, null, [
            'ok' => true,
            'channel' => $channel->data,
        ]);

        $this->watchPromise($message->getChannel()->then(function (Channel $channel) {
            $this->assertSame('C1234', $channel->getId());
        }));
    }

    public function testSetUser()
    {
        $user = new User($this->client, ['id' => 'C1234']);
        $message = $this->builder->setUser($user)->create();

        $this->mockResponse(200, null, [
            'ok' => true,
            'user' => $user->data,
        ]);

        $this->watchPromise($message->getUser()->then(function (User $user) {
            $this->assertSame('C1234', $user->getId());
        }));
    }

    public function testAddAttachment()
    {
        $attachment = new Attachment('title', 'text', 'fallback');
        $message = $this->builder->addAttachment($attachment)->create();

        $this->assertTrue($message->hasAttachments());
        $this->assertCount(1, $message->getAttachments());
        $this->assertEquals($attachment, $message->getAttachments()[0]);
    }
}
