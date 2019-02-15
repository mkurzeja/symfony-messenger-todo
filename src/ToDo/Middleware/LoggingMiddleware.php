<?php

namespace ToDo\Middleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

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
        $this->logger->debug(sprintf('Started handling of %s', \get_class($message)));

        $result = $stack->next()->handle($envelope, $stack);

        $this->logger->debug(sprintf('Finished handling of %s', \get_class($message)));

        return $result;
    }
}
