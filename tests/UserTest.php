<?php
namespace Slack\Tests;

use Slack\ApiClient;
use Slack\User;

class UserTest extends ClientTestCase
{
    public function testUsername()
    {
        $username = $this->faker->userName;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'name' => $username,
        ]);

        $this->assertEquals($username, $user->getUsername());
    }

    public function testFirstName()
    {
        $firstName = $this->faker->firstName;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'first_name' => $firstName,
            ],
        ]);

        $this->assertEquals($firstName, $user->getFirstName());
    }

    public function testLastName()
    {
        $lastName = $this->faker->lastName;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'last_name' => $lastName,
            ],
        ]);

        $this->assertEquals($lastName, $user->getLastName());
    }

    public function testRealName()
    {
        $name = $this->faker->name;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'real_name' => $name,
            ],
        ]);

        $this->assertEquals($name, $user->getRealName());
    }

    public function testEmail()
    {
        $email = $this->faker->email;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'email' => $email,
            ],
        ]);

        $this->assertEquals($email, $user->getEmail());
    }

    public function testPhone()
    {
        $phone = $this->faker->phoneNumber;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'phone' => $phone,
            ],
        ]);

        $this->assertEquals($phone, $user->getPhone());
    }

    public function testSkype()
    {
        $name = $this->faker->userName;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'profile' => [
                'skype' => $name,
            ],
        ]);

        $this->assertEquals($name, $user->getSkype());
    }

    public function testIsAdmin()
    {
        $is = $this->faker->boolean;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'is_admin' => $is,
        ]);

        $this->assertEquals($is, $user->isAdmin());
    }

    public function testIsOwner()
    {
        $is = $this->faker->boolean;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'is_owner' => $is,
        ]);

        $this->assertEquals($is, $user->isOwner());
    }

    public function testIsPrimaryOwner()
    {
        $is = $this->faker->boolean;

        $client = $this->getMockBuilder(ApiClient::class)->getMock();
        $user = new User($client, [
            'is_primary_owner' => $is,
        ]);

        $this->assertEquals($is, $user->isPrimaryOwner());
    }
}
