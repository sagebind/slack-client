<?php
namespace Slack\Async;

use GuzzleHttp\Promise\Promise as GuzzlePromise;
use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;
use React\Promise\PromiseInterface as ReactPromiseInterface;

/**
 * A Frankenstein promise class that allows the Slack client to utilize both
 * ReactPHP and Guzzle asynchronous functions.
 */
class Promise extends GuzzlePromise implements GuzzlePromiseInterface, ReactPromiseInterface
{
    /**
     * {@inheritDoc}
     *
     * Doesn't ever call onProgress functions.
     */
    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null)
    {
        return parent::then($onFulfilled, $onRejected);
    }
}
