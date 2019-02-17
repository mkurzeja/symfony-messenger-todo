<?php

namespace ToDo\Domain\Command\Handler;

use Symfony\Component\Messenger\MessageBus;
use ToDo\Domain\Command\AddNewTodo;
use ToDo\Domain\Event\TodoAdded;
use ToDo\Domain\ToDo;
use ToDo\Domain\ToDoRepository;

class AddNewToDoHandler
{
    /**
     * @var ToDoRepository
     */
    private $repository;

    /**
     * @var MessageBus
     */
    private $eventBus;

    public function __construct(ToDoRepository $repository, MessageBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(AddNewTodo $addNewTodo)
    {
        $todo = new ToDo(
            $addNewTodo->uuid(),
            $addNewTodo->task(),
            $addNewTodo->done(),
            $addNewTodo->deadline()
        );

        $this->repository->save($todo);

        $todoAdded = TodoAdded::fromCommand($addNewTodo);
        $this->eventBus->dispatch($todoAdded);
    }
}
