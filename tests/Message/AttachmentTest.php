<?php
namespace Slack\Tests\Message;

use Slack\Message\Attachment;
use Slack\Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function testGetFallbackText()
    {
        $attachment = Attachment::fromData([
            'fallback' => $this->faker->sentence,
        ]);

        $this->assertEquals($attachment->data['fallback'], $attachment->getFallbackText());
    }

    public function testGetColor()
    {
        $attachment = Attachment::fromData([
            'color' => $this->faker->hexColor,
        ]);

        $this->assertEquals($attachment->data['color'], $attachment->getColor());
    }

    public function testGetPretext()
    {
        $attachment = Attachment::fromData([
            'pretext' => $this->faker->sentence,
        ]);

        $this->assertEquals($attachment->data['pretext'], $attachment->getPretext());
    }

    public function testGetAuthorName()
    {
        $attachment = Attachment::fromData([
            'author_name' => $this->faker->name,
        ]);

        $this->assertEquals($attachment->data['author_name'], $attachment->getAuthorName());
    }

    public function testGetAuthorLink()
    {
        $attachment = Attachment::fromData([
            'author_link' => $this->faker->url,
        ]);

        $this->assertEquals($attachment->data['author_link'], $attachment->getAuthorLink());
    }

    public function testGetAuthorIcon()
    {
        $attachment = Attachment::fromData([
            'author_icon' => $this->faker->url,
        ]);

        $this->assertEquals($attachment->data['author_icon'], $attachment->getAuthorIcon());
    }

    public function testGetTitle()
    {
        $attachment = Attachment::fromData([
            'title' => $this->faker->title,
        ]);

        $this->assertEquals($attachment->data['title'], $attachment->getTitle());
    }

    public function testGetTitleLink()
    {
        $attachment = Attachment::fromData([
            'title_link' => $this->faker->url,
        ]);

        $this->assertEquals($attachment->data['title_link'], $attachment->getTitleLink());
    }

    public function testGetText()
    {
        $attachment = Attachment::fromData([
            'text' => $this->faker->sentence,
        ]);

        $this->assertEquals($attachment->data['text'], $attachment->getText());
    }

    public function testGetImageUrl()
    {
        $attachment = Attachment::fromData([
            'image_url' => $this->faker->url,
        ]);

        $this->assertEquals($attachment->data['image_url'], $attachment->getImageUrl());
    }

    public function testGetThumbUrl()
    {
        $attachment = Attachment::fromData([
            'thumb_url' => $this->faker->url,
        ]);

        $this->assertEquals($attachment->data['thumb_url'], $attachment->getThumbUrl());
    }
}
