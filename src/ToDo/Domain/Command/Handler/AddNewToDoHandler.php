<?php

namespace ToDo\Domain\Command\Handler;

use ToDo\Domain\Command\AddNewTodo;
use ToDo\Domain\ToDo;
use ToDo\Domain\ToDoRepository;

class AddNewToDoHandler
{
    /**
     * @var ToDoRepository
     */
    private $repository;

    public function __construct(ToDoRepository $repository)
    {
        $this->repository = $repository;
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
    }
}
