<?php
namespace Slack\Tests\Message;

use Slack\Message\AttachmentField;
use Slack\Tests\TestCase;

class AttachmentFieldTest extends TestCase
{
    public function testConstructor()
    {
        $title = $this->faker->title;
        $value = $this->faker->sentence;
        $short = $this->faker->boolean;

        $field = new AttachmentField($title, $value, $short);

        $this->assertEquals($title, $field->getTitle());
        $this->assertEquals($value, $field->getValue());
        $this->assertEquals($short, $field->isShort());
    }

    public function testGetTitle()
    {
        $field = AttachmentField::fromData([
            'title' => $this->faker->title,
        ]);

        $this->assertEquals($field->data['title'], $field->getTitle());
    }

    public function testGetValue()
    {
        $field = AttachmentField::fromData([
            'value' => $this->faker->sentence,
        ]);

        $this->assertEquals($field->data['value'], $field->getValue());
    }

    public function testIsShort()
    {
        $field = AttachmentField::fromData([
            'short' => $this->faker->boolean,
        ]);

        $this->assertEquals($field->data['short'], $field->isShort());
    }
}
