<?php

namespace ToDo\Infrastructure\Repository;

use ToDo\Domain\ToDo;
use ToDo\Domain\ToDoRepository;

class InMemoryTodoRepository implements ToDoRepository
{
    private $todos = [];

    public function save(ToDo $todo)
    {
        $this->todos[$todo->getUuid()->toString()] = $todo;
    }

    /**
     * @return ToDo[]
     */
    public function findAll()
    {
        return $this->todos;
    }
}
