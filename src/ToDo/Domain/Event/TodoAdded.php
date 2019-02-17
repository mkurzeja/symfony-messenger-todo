<?php

namespace ToDo\Domain\Event;

use Ramsey\Uuid\UuidInterface;
use ToDo\Domain\Command\AddNewTodo;

class TodoAdded
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

    public function __construct(
        UuidInterface $uuid,
        string $task,
        \DateTimeImmutable $deadline = null,
        bool $done = false
    ) {
        $this->uuid = $uuid;
        $this->task = $task;
        $this->deadline = $deadline;
        $this->done = $done;
    }

    public static function fromCommand(AddNewTodo $addNewTodo): TodoAdded
    {
        return new self($addNewTodo->uuid(), $addNewTodo->task(), $addNewTodo->deadline(), $addNewTodo->done());
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function task(): string
    {
        return $this->task;
    }

    public function deadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }

    public function done(): bool
    {
        return $this->done;
    }
}
