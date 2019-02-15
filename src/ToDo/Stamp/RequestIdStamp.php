<?php

namespace ToDo\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class RequestIdStamp implements StampInterface
{
    /**
     * @var string
     */
    private $requestId;

    public function __construct(string $requestId)
    {
        $this->requestId = $requestId;
    }

    public function requestId(): string
    {
        return $this->requestId;
    }
}
