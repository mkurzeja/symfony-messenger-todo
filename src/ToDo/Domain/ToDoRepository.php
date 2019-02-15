<?php

namespace ToDo\Domain;

interface ToDoRepository
{
    public function save(ToDo $todo);

    /**
     * @return ToDo[]
     */
    public function findAll();
}
