<?php

namespace Slack;

/**
 * Contains information about a bot.
 */
class Bot extends ClientObject
{
    /**
     * Gets the bot's ID.
     *
     * @return string The bot's ID.
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * Gets the name of the bot.
     *
     * @return string The name of the bot.
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * Checks if the bot is deleted.
     *
     * @return bool True if the bot is deleted.
     */
    public function isDeleted()
    {
        return $this->data['deleted'];
    }

    /**
      * Bot icon image URL 36x36px
      *
      * @return string URL of the 36x36px bot icon image
      */
     public function getIconImage36()
     {
         return $this->data['icons']['image_36'];
     }

     /**
      * Bot icon image URL 48x48px
      *
      * @return string URL of the 48x48px bot icon image
      */
     public function getIconImage48()
     {
         return $this->data['icons']['image_48'];
     }

     /**
      * Bot icon image URL 72x72px
      *
      * @return string URL of the 72x72px bot icon image
      */
     public function getIconImage72()
     {
         return $this->data['icons']['image_72'];
     }
}
