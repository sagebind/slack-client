<?php
namespace Slack\Tests;

abstract class ClientObjectTestCase extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }
}
