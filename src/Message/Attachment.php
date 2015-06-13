<?php
namespace Slack\Message;

use Slack\DataObject;

/**
 * A message attachment containing rich text data.
 *
 * @see https://api.slack.com/docs/attachments
 */
class Attachment extends DataObject
{
    /**
     * Creates a new message attachment.
     *
     * @param string $title    The attachment title.
     * @param string $text     The attachment body text.
     * @param string $fallback A plain-text summary of the attachment.
     */
    public function __construct($title, $text, $fallback = null, $color = null, $pretext = null, array $fields = [])
    {
        $this->data['title'] = $title;
        $this->data['text'] = $text;
        $this->data['fallback'] = $fallback ?: $text;
        $this->data['color'] = $color;
        $this->data['pretext'] = $pretext;
        $this->data['fields'] = $fields;
    }

    /**
     * Gets a plain-text summary of the attachment.
     *
     * @return string A plain-text summary of the attachment.
     */
    public function getFallbackText()
    {
        return $this->data['fallback'];
    }

    /**
     * Gets the attachment border color.
     *
     * @return string The attachment border color. Can be `good`, `warning`,
     *                `danger`, or a hex color code.
     */
    public function getColor()
    {
        return isset($this->data['color']) ? $this->data['color'] : null;
    }

    /**
     * Gets the attachment pretext.
     *
     * @return string Optional text that appears above the message attachment block.
     */
    public function getPretext()
    {
        return isset($this->data['pretext']) ? $this->data['pretext'] : '';
    }

    /**
     * Gets the author name.
     *
     * @return string The attachment author's name.
     */
    public function getAuthorName()
    {
        return isset($this->data['author_name']) ? $this->data['author_name'] : null;
    }

    /**
     * Gets the author link.
     *
     * @return string A link URL for the author.
     */
    public function getAuthorLink()
    {
        return isset($this->data['author_link']) ? $this->data['author_link'] : null;
    }

    /**
     * Gets the author icon.
     *
     * @return string An icon URL to show next to the author name.
     */
    public function getAuthorIcon()
    {
        return isset($this->data['author_icon']) ? $this->data['author_icon'] : null;
    }

    /**
     * Gets the title.
     *
     * @return string The attachment title.
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * Gets the title link.
     *
     * @return string A link URL the title should link to.
     */
    public function getTitleLink()
    {
        return isset($this->data['title_link']) ? $this->data['title_link'] : null;
    }

    /**
     * Gets the attachment body text.
     *
     * @return string The attachment body text.
     */
    public function getText()
    {
        return $this->data['text'];
    }

    /**
     * Gets the image URL.
     *
     * @return string A URL to an image to display in the attachment body.
     */
    public function getImageUrl()
    {
        return isset($this->data['image_url']) ? $this->data['image_url'] : null;
    }

    /**
     * Gets the thumbnail URL.
     *
     * @return string A URL to an image to display as a thumbnail.
     */
    public function getThumbUrl()
    {
        return isset($this->data['thumb_url']) ? $this->data['thumb_url'] : null;
    }

    /**
     * Checks if the attachment has fields.
     *
     * @return bool
     */
    public function hasFields()
    {
        return isset($this->data['fields']) && count($this->data['fields']) > 0;
    }

    /**
     * Gets all the attachment's fields.
     *
     * @return AttachmentField[]
     */
    public function getFields()
    {
        return isset($this->data['fields']) ? $this->data['fields'] : [];
    }

    /**
     * {@inheritDoc}
     */
    public function jsonUnserialize(array $data)
    {
        if (!isset($this->data['fields'])) {
            return;
        }

        for ($i = 0; $i < count($this->data['fields']); $i++) {
            $this->data['fields'][$i] = AttachmentField::fromData($this->data['fields'][$i]);
        }
    }
}
