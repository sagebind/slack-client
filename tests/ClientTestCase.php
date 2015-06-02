<?php
namespace Slack\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

/**
 * Base helper class for test cases for mocking API requests and responses.
 */
abstract class ClientTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Faker\Generator A Faker fake data generator.
     */
    protected $faker;

    /**
     * @var Client A Guzzle HTTP client.
     */
    protected $guzzle;

    /**
     * @var History A Guzzle request history subscriber.
     */
    protected $history;

    /**
     * @var Mock A Guzzle response mocker.
     */
    protected $mock;

    /**
     * Sets up a test with some useful mock objects.
     */
    public function setUp()
    {
        // create a mock handler
        $this->mock = new MockHandler();
        $handler = HandlerStack::create($this->mock);

        // add history middleware to the client for inspecting requests
        $this->history = [];
        $handler->push(Middleware::history($this->history));

        // create a Guzzle client whose requests can be inspected and responses
        // are mocked
        $this->guzzle = new Client(['handler' => $handler]);

        // create faker instance for faking data
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Adds a response to be mocked for a Guzzle request.
     *
     * @param int        $statusCode
     * @param array|null $headers
     * @param mixed      $body
     */
    protected function mockResponse($statusCode, array $headers = null, $body = null)
    {
        // automatic conversion to JSON
        if (is_array($body)) {
            $body = json_encode($body);
        }

        // create the response object
        $response = new Response($statusCode, $headers, $body);

        // add response to mock queue
        $this->mock->append($response);
    }

    /**
     * Makes an assertion on the URL of the last Guzzle request.
     *
     * @param string $url
     */
    protected function assertLastRequestUrl($url)
    {
        $this->assertNotEmpty($this->history);
        $this->assertEquals($url, end($this->history)['request']->getUri());
    }
}
