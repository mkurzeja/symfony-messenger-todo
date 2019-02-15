<?php

namespace ToDo\Domain;

use Ramsey\Uuid\UuidInterface;

class ToDo
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
     * @var bool
     */
    private $done;

    /**
     * @var \DateTimeImmutable
     */
    private $deadline;

    /**
     * ToDo constructor.
     *
     * @param UuidInterface      $uuid
     * @param string             $task
     * @param bool               $done
     * @param \DateTimeImmutable $deadline
     */
    public function __construct(UuidInterface $uuid, string $task, bool $done, \DateTimeImmutable $deadline = null)
    {
        $this->uuid = $uuid;
        $this->task = $task;
        $this->done = $done;
        $this->deadline = $deadline;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDeadline(): ?\DateTimeImmutable
    {
        return $this->deadline;
    }
}
