<?php
namespace Slack\Message;

use Slack\DataObject;

/**
 * A field inside a message attachment.
 *
 * @see https://api.slack.com/docs/attachments
 */
class AttachmentField extends DataObject
{
    /**
     * Creates a new attachment field.
     *
     * @param string $title A text heading for the field.
     * @param string $value The text value of the field.
     * @param bool   $short Indicates if the value can be displayed side-by-side with other values.
     */
    public function __construct($title, $value, $short = true)
    {
        $this->data['title'] = $title;
        $this->data['value'] = $value;
        $this->data['short'] = $short;
    }

    /**
     * Gets the text heading for the field.
     *
     * @return string The text heading for the field.
     */
    public function getTitle()
    {
        return $this->data['title'];
    }

    /**
     * Gets the text value of the field.
     *
     * @return string The text value of the field.
     */
    public function getValue()
    {
        return $this->data['value'];
    }

    /**
     * Checks if the value can be displayed side-by-side with other values.
     *
     * @return bool True if the value is short, otherwise false.
     */
    public function isShort()
    {
        return isset($this->data['short']) && (bool)$this->data['short'];
    }
}
