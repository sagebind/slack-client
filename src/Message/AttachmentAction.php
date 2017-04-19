<?php
namespace Slack\Message;

use Slack\DataObject;

/**
 * An action inside a message attachment.
 *
 * @see https://api.slack.com/docs/attachments
 */
class AttachmentAction extends DataObject
{
    /**
     * Creates a new attachment action.
     *
     * @param string $name A text heading for the field.
     * @param string $text The text value of the field.
     * @param string $type The type value of the field.
     * @param string $value The value of the field.
     * @param array $confirm Array for button confirmation
     */
    public function __construct($name, $text, $type, $value, array $confirm = null)
    {
        $this->data['name'] = $name;
        $this->data['text'] = $text;
        $this->data['type'] = $type;
        $this->data['value'] = $value;

        if (isset($confirm)) {
            $this->data['confirm'] = $confirm;
        }
    }

    /**
     * Gets the text heading for the field.
     *
     * @return string The text heading for the field.
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * Gets the text value of the field.
     *
     * @return string The text value of the field.
     */
    public function getText()
    {
        return $this->data['text'];
    }

    /**
     * Gets the text type of the field.
     *
     * @return string The type value of the field.
     */
    public function getType()
    {
        return $this->data['type'];
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
     * Gets the confirmation details for the action.
     *
     * @return array Array containing the confirmation.
     */
    public function getConfirm()
    {
        return isset($this->data['confirm']) ? $this->data['confirm'] : '';
    }
}
