<?php
namespace Slack\Tests;

use Slack\Team;

class TeamTest extends TestCase
{
    public function testGetId()
    {
        $id = $this->faker->uuid;
        $team = new Team($this->client, [
            'id' => $id,
        ]);

        $this->assertEquals($id, $team->getId());
    }

    public function testGetName()
    {
        $name = $this->faker->title;
        $team = new Team($this->client, [
            'name' => $name,
        ]);

        $this->assertEquals($name, $team->getName());
    }

    public function testGetDomain()
    {
        $domain = $this->faker->word;
        $team = new Team($this->client, [
            'domain' => $domain,
        ]);

        $this->assertEquals($domain, $team->getDomain());
    }

    public function testGetEmailDomain()
    {
        $domain = $this->faker->url;
        $team = new Team($this->client, [
            'email_domain' => $domain,
        ]);

        $this->assertEquals($domain, $team->getEmailDomain());
    }
}
