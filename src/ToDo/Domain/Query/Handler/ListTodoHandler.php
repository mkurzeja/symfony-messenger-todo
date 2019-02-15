<?php

namespace ToDo\Domain\Query\Handler;

use ToDo\Domain\Query\ListTodo;
use ToDo\Domain\ToDoRepository;

class ListTodoHandler
{
    /**
     * @var ToDoRepository
     */
    private $repository;

    public function __construct(ToDoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ListTodo $listTodo)
    {
        return $this->repository->findAll();
    }
}
