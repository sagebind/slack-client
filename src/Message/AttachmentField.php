<?php
namespace Slack\Message;

/**
 * A field inside a message attachment.
 *
 * @see https://api.slack.com/docs/attachments
 */
class AttachmentField
{
    /**
     * @var string The text heading for the field.
     */
    public $title;

    /**
     * @var string The text value of the field.
     */
    public $value;

    /**
     * @var bool Indicates if the value can be displayed side-by-side with other values.
     */
    public $isShort;

    /**
     * Creates a new attachment field.
     *
     * @param string $title A text heading for the field.
     * @param string $value The text value of the field.
     * @param bool   $short Indicates if the value can be displayed side-by-side with other values.
     */
    public function __construct($title, $value, $short = true)
    {
        $this->title = $title;
        $this->value = $value;
        $this->isShort = $short;
    }
}
