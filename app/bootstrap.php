<?php
require_once __DIR__.'/../vendor/autoload.php';

use Pimple\Container;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use ToDo\Command\AddTodoCommand;
use ToDo\Command\ListTodoCommand;
use ToDo\Domain\Command\AddNewTodo;
use ToDo\Domain\Command\Handler\AddNewToDoHandler;
use ToDo\Domain\ToDoRepository;
use ToDo\Infrastructure\Repository\InMemoryTodoRepository;

$container = new Container();
$c = $container;

$c[ToDoRepository::class] = function ($c) {
    return new InMemoryTodoRepository();
};

/*
 * Command bus
 */
$c['command_bus.handler_locator'] = function ($c) {
    return new HandlersLocator([
        AddNewTodo::class => [AddNewToDoHandler::class => $c[AddNewToDoHandler::class]],
    ]);
};

$c['command_bus'] = function ($c) {
    return new MessageBus(
        [
            new HandleMessageMiddleware($c['command_bus.handler_locator']),
        ]
    );
};

$c[AddNewToDoHandler::class] = function ($c) {
    return new AddNewToDoHandler($c[ToDoRepository::class]);
};

/*
 * CLI Commands
 */
$c[AddTodoCommand::class] = function ($c) {
    $command = new AddTodoCommand();
    $command->setContainer($c);

    return $command;
};
$c[ListTodoCommand::class] = function ($c) {
    $command = new ListTodoCommand();
    $command->setContainer($c);

    return $command;
};
