<?php

namespace ToDo\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use ToDo\Stamp\RequestIdStamp;

class LoggingMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggingMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $requestId = null;
        $requestIdStamps = $envelope->all(RequestIdStamp::class);

        if (count($requestIdStamps)) {
            $requestId = $requestIdStamps[0]->requestId();
        }

        $this->logger->debug(sprintf('[%s] Started handling of %s', $requestId, \get_class($message)));

        $result = $stack->next()->handle($envelope, $stack);

        $this->logger->debug(sprintf('[%s] Finished handling of %s', $requestId, \get_class($message)));

        return $result;
    }
}
