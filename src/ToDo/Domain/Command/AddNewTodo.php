<?php

namespace ToDo\Domain\Command;

use Ramsey\Uuid\UuidInterface;

class AddNewTodo
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var string
     */
    private $task;

    /**
     * @var \DateTimeImmutable
     */
    private $deadline;

    /**
     * @var bool
     */
    private $done;

    public function __construct(UuidInterface $uuid, string $task, bool $done, \DateTimeImmutable $deadline = null)
    {
        $this->task = $task;
        $this->deadline = $deadline;
        $this->done = $done;
        $this->uuid = $uuid;
    }

    public static function add(UuidInterface $uuid, string $task, \DateTimeImmutable $deadline = null): AddNewTodo
    {
        return new self($uuid, $task, false, $deadline);
    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function task(): string
    {
        return $this->task;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function deadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }

    /**
     * @return bool
     */
    public function done(): bool
    {
        return $this->done;
    }
}
