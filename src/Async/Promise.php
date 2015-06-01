<?php
namespace Slack\Async;

use GuzzleHttp\Promise as GuzzlePromise;
use GuzzleHttp\Promise\PromiseInterface as GuzzlePromiseInterface;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface as ReactPromiseInterface;

/**
 * A Frankenstein promise class that allows the Slack client to utilize both
 * ReactPHP and Guzzle asynchronous functions.
 */
class Promise extends GuzzlePromise\Promise implements GuzzlePromiseInterface, ReactPromiseInterface
{
    /**
     * Adds a Guzzle task queue to a React event loop.
     *
     * @param LoopInterface $loop The event loop to add to.
     */
    public static function runQueue(LoopInterface $loop)
    {
        // Add the Guzzle queue to the React event loop via a periodic timer,
        // which will be invoked when there are no more nextTick() callbacks.
        // The timer self-cancels when the Guzzle queue reports that it is empty.
        $queueTimer = $loop->addPeriodicTimer(0, function () use (&$queueTimer) {
            $queue = GuzzlePromise\queue();
            $queue->run();

            if ($queue->isEmpty()) {
                $queueTimer->cancel();
            }
        });
    }

    /**
     * Creates a resolved promise with a given value.
     *
     * @param mixed $value The value of the promise.
     *
     * @return Promise A resolved promise.
     */
    public static function resolved($value)
    {
        $promise = new static();
        $promise->resolve($value);
        return $promise;
    }

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
