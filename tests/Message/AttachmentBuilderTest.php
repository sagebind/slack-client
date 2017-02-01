<?php
namespace Slack\Tests\Message;

use Slack\Message\Attachment;
use Slack\Message\AttachmentBuilder;
use Slack\Message\AttachmentField;
use Slack\Tests\TestCase;

class AttachmentBuilderTest extends TestCase
{
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new AttachmentBuilder();
    }

    public function testCreateReturnsAttachment()
    {
        $attachment = $this->builder->create();
        $this->assertInstanceOf(Attachment::class, $attachment);
    }

    public function testSetText()
    {
        $attachment = $this->builder->setText('text')->create();
        $this->assertEquals('text', $attachment->getText());
    }

    public function testSetTimestamp()
    {
        $now = new \DateTime();
        $attachment = $this->builder->setTimestamp($now)->create();

        $this->assertEquals($now->getTimestamp(), $attachment->getTimestamp()->getTimestamp());
    }

    public function testAddField()
    {
        $field = new AttachmentField('title', 'text');
        $attachment = $this->builder->addField($field)->create();

        $this->assertTrue($attachment->hasFields());
        $this->assertCount(1, $attachment->getFields());
        $this->assertEquals($field, $attachment->getFields()[0]);
    }
}
