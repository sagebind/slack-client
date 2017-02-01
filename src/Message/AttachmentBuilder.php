<?php
namespace Slack\Message;

/**
 * A builder object for creating new message attachment objects.
 */
class AttachmentBuilder
{
    // An array of data to pass to the built attachment.
    private $data = [];

    // Keep track of which text values should be parsed as Markdown.
    private $markdownInText = false;
    private $markdownInPretext = false;
    private $markdownInFields = false;

    /**
     * Sets the attachment title with an optional link.
     *
     * @param string $title The attachment title text.
     * @param string $link An optional URL the title should link to.
     * @return $this
     */
    public function setTitle($title, $link = null)
    {
        $this->data['title'] = $title;
        if ($link) {
            $this->data['title_link'] = $link;
        }

        return $this;
    }

    /**
     * Sets the main text of the attachment.
     *
     * @param string $text The attachment text.
     * @param bool $markdown Enables or disables Markdown parsing in the text.
     * @return $this
     */
    public function setText($text, $markdown = false)
    {
        $this->data['text'] = $text;
        $this->markdownInText = $markdown;

        return $this;
    }

    /**
     * Sets a plain-text summary of the attachment.
     *
     * This text will be used in clients that don't show formatted text.
     *
     * @param string $fallbackText The fallback text.
     * @return $this
     */
    public function setFallbackText($fallbackText)
    {
        $this->data['fallback'] = $fallbackText;

        return $this;
    }

    /**
     * Sets the attachment pretext.
     *
     * This is optional text that appears above the message attachment block.
     *
     * @param string $pretext The attachment pretext.
     * @param bool $markdown Enables or disables Markdown parsing in the pretext.
     * @return $this
     */
    public function setPretext($pretext, $markdown = false)
    {
        $this->data['pretext'] = $pretext;
        $this->markdownInPretext = $markdown;

        return $this;
    }

    /**
     * Sets the attachment border color.
     *
     * @param string $color The attachment border color. Can be "good", "warning", "danger", or a hex color code.
     * @return $this
     */
    public function setColor($color)
    {
        $this->data['color'] = $color;

        return $this;
    }

    /**
     * Sets the message author.
     *
     * @param string $name The author name.
     * @param string $link An optional URL that the author text should link to.
     * @param string $icon An optional URL to an image to show to the left of the author name.
     * @return $this
     */
    public function setAuthor($name, $link = null, $icon = null)
    {
        $this->data['author_name'] = $name;
        if ($link) {
            $this->data['author_link'] = $link;
        }
        if ($icon) {
            $this->data['author_icon'] = $icon;
        }

        return $this;
    }

    /**
     * Sets the URL to an image to display in the attachment body.
     *
     * @param string $url The image URL.
     * @return $this
     */
    public function setImageUrl($url)
    {
        $this->data['image_url'] = $url;

        return $this;
    }

    /**
     * Sets the URL to an image to display as a thumbnail.
     *
     * @param string $url The thumbnail URL.
     * @return $this
     */
    public function setThumbUrl($url)
    {
        $this->data['thumb_url'] = $url;

        return $this;
    }

    /**
     * Sets an attachment footer shown beneath the attachment body.
     *
     * @param string $text Brief footer text.
     * @param string $icon An optional URL to an image to show to the left of the footer text.
     * @return $this
     */
    public function setFooter($text, $icon = null)
    {
        $this->data['footer'] = $text;
        if ($icon) {
            $this->data['footer_icon'] = $icon;
        }

        return $this;
    }

    /**
     * Sets an additional timestamp to show in the attachment footer.
     *
     * @param \DateTime $time A timestamp.
     * @return $this
     */
    public function setTimestamp(\DateTime $time)
    {
        $this->data['ts'] = $time->getTimestamp();

        return $this;
    }

    /**
     * Adds a field to the attachment.
     *
     * @param AttachmentField $field The field to add.
     * @return $this
     */
    public function addField(AttachmentField $field)
    {
        if (!isset($this->data['fields'])) {
            $this->data['fields'] = [];
        }

        $this->data['fields'][] = $field->data;

        return $this;
    }

    /**
     * Enables or disables Markdown parsing in fields.
     *
     * @param bool $enable Whether Markdown should be enabled.
     * @return $this
     */
    public function enableMarkdownFields($enable = true)
    {
        $this->markdownInFields = !!$enable;

        return $this;
    }

    /**
     * Creates and returns a new attachment object specified by the builder.
     *
     * @return Attachment A new attachment object.
     */
    public function create()
    {
        $this->data['mrkdwn_in'] = [];

        if ($this->markdownInText) {
            $this->data['mrkdwn_in'][] = 'text';
        }

        if ($this->markdownInPretext) {
            $this->data['mrkdwn_in'][] = 'pretext';
        }

        if ($this->markdownInFields) {
            $this->data['mrkdwn_in'][] = 'fields';
        }

        return Attachment::fromData($this->data);
    }
}
