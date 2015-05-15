<?php
namespace Slackyboy\Slack;

/**
 * Contains information about a Slack team.
 */
class Team extends Channel
{
    /**
     * Gets the name of the team.
     *
     * @return string The name of the team.
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * Gets the team's domain name.
     *
     * @return string The domain name of the team.
     */
    public function getDomain()
    {
        return $this->data['domain'];
    }

    /**
     * Gets the domain name emails are restricted to, if any.
     *
     * @return string An email domain name.
     */
    public function getEmailDomain()
    {
        return isset($this->data['email_domain']) ? $this->data['email_domain'] : '';
    }
}
