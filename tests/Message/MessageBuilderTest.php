<?php
namespace Slack\Tests\Message;

use Slack\ApiClient;
use Slack\Message\Attachment;
use Slack\Message\Message;
use Slack\Message\MessageBuilder;

class MessageBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $builder;

    public function setUp()
    {
        $this->builder = new MessageBuilder($this->getMockBuilder(ApiClient::class)
            ->disableOriginalConstructor()->getMock());
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

    public function testAddAttachment()
    {
        $attachment = new Attachment('title', 'text', 'fallback');
        $message = $this->builder->addAttachment($attachment)->create();

        $this->assertCount(1, $message->getAttachments());
        $this->assertEquals($attachment, $message->getAttachments()[0]);
    }
}
