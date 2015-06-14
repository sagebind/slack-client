<?php
namespace Slack\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use React\EventLoop\Factory;
use React\Promise\ExtendedPromiseInterface;
use Slack\ApiClient;

/**
 * Base helper class for test cases for mocking API requests and responses.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
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
     * @var \React\EventLoop\LoopInterface A React event loop.
     */
    protected $loop;

    /**
     * @var ApiClient A mocked Slack API client.
     */
    protected $client;

    /**
     * @var \SplQueue A queue of unwrapped exceptions to throw.
     */
    private $exceptionQueue;

    /**
     * Sets up a test with some useful mock objects.
     */
    public function setUp()
    {
        // Create a mock handler
        $this->mock = new MockHandler();
        $handler = HandlerStack::create($this->mock);

        // Add history middleware to the client for inspecting requests
        $this->history = [];
        $handler->push(Middleware::history($this->history));

        // Create a Guzzle client whose requests can be inspected and responses
        // are mocked
        $this->guzzle = new Client(['handler' => $handler]);

        // Create faker instance for faking data
        $this->faker = \Faker\Factory::create();

        // Create an event loop
        $this->loop = Factory::create();

        // Create the API client
        $this->client = new ApiClient($this->loop, $this->guzzle);

        // Initialize exception queue
        $this->exceptionQueue = new \SplQueue();
    }

    /**
     * Works in conjunction with watchPromise() to unwrap promise exceptions.
     */
    public function tearDown()
    {
        $this->loop->run();

        if (!$this->exceptionQueue->isEmpty()) {
            // We can only really throw the first exception
            throw $this->exceptionQueue->dequeue();
        }
    }

    /**
     * Watches a promise and unwraps it if it is rejected.
     *
     * This method should be used on promises that have assertions inside of them
     * so that their assertions bubble up and are caught by PHPUnit.
     *
     * @param ExtendedPromiseInterface $promise The promise to watch.
     */
    final public function watchPromise(ExtendedPromiseInterface $promise)
    {
        $promise->done(null, function (\Exception $exception) {
            $this->exceptionQueue->enqueue($exception);
        });
    }

    /**
     * Adds a response to be mocked for a Guzzle request.
     *
     * @param int        $statusCode
     * @param array|null $headers
     * @param mixed      $body
     */
    final public function mockResponse($statusCode, array $headers = null, $body = null)
    {
        // automatic conversion to JSON
        if (is_array($body)) {
            $body = json_encode($body);
        }

        // create the response object
        $response = new Response($statusCode, $headers ?: [], $body);

        // add response to mock queue
        $this->mock->append($response);
    }

    /**
     * Makes an assertion on the URL of the last Guzzle request.
     *
     * @param string $url
     */
    final public function assertLastRequestUrl($url)
    {
        $this->assertNotEmpty($this->history);
        $this->assertEquals($url, end($this->history)['request']->getUri());
    }
}
