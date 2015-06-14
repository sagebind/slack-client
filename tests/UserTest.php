<?php
namespace Slack\Tests;

use Slack\User;

class UserTest extends TestCase
{
    public function testUsername()
    {
        $username = $this->faker->userName;

        $user = new User($this->client, [
            'name' => $username,
        ]);

        $this->assertEquals($username, $user->getUsername());
    }

    public function testFirstName()
    {
        $firstName = $this->faker->firstName;

        $user = new User($this->client, [
            'profile' => [
                'first_name' => $firstName,
            ],
        ]);

        $this->assertEquals($firstName, $user->getFirstName());
    }

    public function testLastName()
    {
        $lastName = $this->faker->lastName;

        $user = new User($this->client, [
            'profile' => [
                'last_name' => $lastName,
            ],
        ]);

        $this->assertEquals($lastName, $user->getLastName());
    }

    public function testRealName()
    {
        $name = $this->faker->name;

        $user = new User($this->client, [
            'profile' => [
                'real_name' => $name,
            ],
        ]);

        $this->assertEquals($name, $user->getRealName());
    }

    public function testEmail()
    {
        $email = $this->faker->email;

        $user = new User($this->client, [
            'profile' => [
                'email' => $email,
            ],
        ]);

        $this->assertEquals($email, $user->getEmail());
    }

    public function testPhone()
    {
        $phone = $this->faker->phoneNumber;

        $user = new User($this->client, [
            'profile' => [
                'phone' => $phone,
            ],
        ]);

        $this->assertEquals($phone, $user->getPhone());
    }

    public function testSkype()
    {
        $name = $this->faker->userName;

        $user = new User($this->client, [
            'profile' => [
                'skype' => $name,
            ],
        ]);

        $this->assertEquals($name, $user->getSkype());
    }

    public function testIsAdmin()
    {
        $is = $this->faker->boolean;

        $user = new User($this->client, [
            'is_admin' => $is,
        ]);

        $this->assertEquals($is, $user->isAdmin());
    }

    public function testIsOwner()
    {
        $is = $this->faker->boolean;

        $user = new User($this->client, [
            'is_owner' => $is,
        ]);

        $this->assertEquals($is, $user->isOwner());
    }

    public function testIsPrimaryOwner()
    {
        $is = $this->faker->boolean;

        $user = new User($this->client, [
            'is_primary_owner' => $is,
        ]);

        $this->assertEquals($is, $user->isPrimaryOwner());
    }

    public function testGetPresence()
    {
        $presence = $this->faker->boolean ? 'active' : 'away';

        $user = new User($this->client, [
            'id' => $this->faker->uuid,
        ]);

        $this->mockResponse(200, null, [
            'ok' => true,
            'presence' => $presence,
        ]);

        $this->watchPromise($user->getPresence()->then(function ($actual) use ($presence) {
            $this->assertEquals($presence, $actual);
        }));
    }
}
