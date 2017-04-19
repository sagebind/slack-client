<?php
namespace Slack\Tests\Message;

use Slack\Message\AttachmentAction;
use Slack\Tests\TestCase;

class AttachmentActionTest extends TestCase
{
    public function testConstructor()
    {
        $name = $this->faker->title;
        $text = $this->faker->title;
        $type = 'button';
        $confirm = null;

        $field = new AttachmentAction($name, $text, $type, $confirm);

        $this->assertEquals($name, $field->getName());
        $this->assertEquals($text, $field->getText());
        $this->assertEquals($type, $field->getType());
        $this->assertEquals($confirm, $field->getConfirm());
    }

    public function testGetName()
    {
        $field = AttachmentAction::fromData([
            'name' => $this->faker->title,
        ]);

        $this->assertEquals($field->data['name'], $field->getName());
    }

    public function testGetText()
    {
        $field = AttachmentAction::fromData([
            'text' => $this->faker->title,
        ]);

        $this->assertEquals($field->data['text'], $field->getText());
    }

    public function testGetType()
    {
        $field = AttachmentAction::fromData([
            'type' => $this->faker->title,
        ]);

        $this->assertEquals($field->data['type'], $field->getType());
    }

    public function testConfirm()
    {
        $field = AttachmentAction::fromData([
            'confirm' => null,
        ]);

        $this->assertEquals($field->data['confirm'], $field->getConfirm());
    }
}
