<?php

namespace ToDo\Infrastructure\Event\Listener;

use ToDo\Domain\Event\TodoAdded;

class SendNewTodoToEmail
{
    public function __invoke(TodoAdded $todoAdded)
    {
        printf("\nNew todo e-mail notification: %s\n", $todoAdded->task());
    }
}
