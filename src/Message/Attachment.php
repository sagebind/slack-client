<?php
namespace Slack\Message;

/**
 * A message attachment containing rich text data.
 *
 * @see https://api.slack.com/docs/attachments
 */
class Attachment
{
    /**
     * @var string A plain-text summary of the attachment.
     */
    public $fallback;

    /**
     * @var string The attachment border color. Can be `good`, `warning`,
     *             `danger`, or a hex color code.
     */
    public $color;

    /**
     * @var string Optional text that appears above the message attachment block.
     */
    public $pretext;

    /**
     * @var string The attachment author's name.
     */
    public $authorName;

    /**
     * @var string A link URL for the author.
     */
    public $authorLink;

    /**
     * @var string An icon URL to show next to the author name.
     */
    public $authorIcon;

    /**
     * @var string The attachment title.
     */
    public $title;

    /**
     * @var string A link URL the title should link to.
     */
    public $titleLink;

    /**
     * @var string The attachment body text.
     */
    public $text;

    /**
     * @var string A URL to an image to display in the attachment body.
     */
    public $imageUrl;

    /**
     * @var string A URL to an image to display as a thumbnail.
     */
    public $thumbUrl;

    /**
     * @var AttachmentField[] An array of attachment fields.
     */
    protected $fields = [];

    /**
     * Creates an attachment object from an array of data.
     *
     * @param array $data The array containing attachment data.
     *
     * @return self A new attachment instance.
     */
    public static function fromData(array $data)
    {
        $attachment = new static($data['title'], $data['text'], $data['fallback']);

        $attachment->color = isset($data['color']) ? $data['color'] : null;
        $attachment->authorName = isset($data['author_name']) ? $data['author_name'] : null;
        $attachment->authorLink = isset($data['author_link']) ? $data['author_link'] : null;
        $attachment->authorIcon = isset($data['author_icon']) ? $data['author_icon'] : null;
        $attachment->titleLink = isset($data['title_link']) ? $data['title_link'] : null;
        $attachment->imageUrl = isset($data['image_url']) ? $data['image_url'] : null;
        $attachment->thumbUrl = isset($data['thumb_url']) ? $data['thumb_url'] : null;

        if (isset($data['fields'])) {
            foreach ($data['fields'] as $fieldData) {
                $attachment->addField(new AttachmentField(
                    $fieldData['title'],
                    $fieldData['value'],
                    isset($fieldData['short']) ?: false));
            }
        }

        return $attachment;
    }

    /**
     * Creates a new message attachment.
     *
     * @param string $title    The attachment title.
     * @param string $text     The attachment body text.
     * @param string $fallback A plain-text summary of the attachment.
     */
    public function __construct($title, $text, $fallback)
    {
        $this->title = $title;
        $this->text = $text;
        $this->fallback = $fallback;
    }

    /**
     * Gets all the attachment's fields.
     *
     * @return AttachmentField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Adds a field to the attachment.
     *
     * @param AttachmentField $field The field to add.
     */
    public function addField(AttachmentField $field)
    {
        $this->fields[] = $field;
    }
}
