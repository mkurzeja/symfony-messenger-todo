<?php

use ToDo\Command\AddTodoCommand;
use ToDo\Command\ListTodoCommand;

if (is_file(getcwd().'/vendor/autoload.php')) {
    require_once getcwd() . '/vendor/autoload.php';
}

require_once __DIR__.'/app/bootstrap.php';

return [
    'commands' => [
        $c[AddTodoCommand::class],
        $c[ListTodoCommand::class],
    ],
];
