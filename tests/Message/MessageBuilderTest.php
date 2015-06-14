<?php
namespace Slack\Tests\Message;

use Slack\Channel;
use Slack\Message\Attachment;
use Slack\Message\Message;
use Slack\Message\MessageBuilder;
use Slack\Tests\TestCase;

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

    public function testAddAttachment()
    {
        $attachment = new Attachment('title', 'text', 'fallback');
        $message = $this->builder->addAttachment($attachment)->create();

        $this->assertTrue($message->hasAttachments());
        $this->assertCount(1, $message->getAttachments());
        $this->assertEquals($attachment, $message->getAttachments()[0]);
    }
}
