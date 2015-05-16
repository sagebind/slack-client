<?php
namespace Slack\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Message\MessageFactory;

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
     * @var MessageFactory A Guzzle factory for creating response objects.
     */
    private $messageFactory;

    /**
     * Sets up a test with some useful mock objects.
     */
    public function setUp()
    {
        $this->messageFactory = new MessageFactory();

        // create a Guzzle client whose requests can be inspected and responses
        // are mocked
        $this->guzzle = new Client();

        // add the mock subscriber to the client, which mocks responses
        $this->mock = new Mock();
        $this->guzzle->getEmitter()->attach($this->mock);

        // add history subscriber to the client for inspecting requests
        $this->history = new History();
        $this->guzzle->getEmitter()->attach($this->history);

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
        $response = $this->messageFactory->createResponse($statusCode, $headers, $body);

        // add response to mock queue
        $this->mock->addResponse($response);
    }

    /**
     * Makes an assertion on the URL of the last Guzzle request.
     *
     * @param string $url
     */
    protected function assertLastRequestUrl($url)
    {
        $this->assertEquals($url, $this->history->getLastRequest()->getUrl());
    }
}
