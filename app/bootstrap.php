<?php
require_once __DIR__.'/../vendor/autoload.php';

use Monolog\Logger;
use Pimple\Container;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use ToDo\Command\AddTodoCommand;
use ToDo\Command\ListTodoCommand;
use ToDo\Domain\Command\AddNewTodo;
use ToDo\Domain\Command\Handler\AddNewToDoHandler;
use ToDo\Domain\Event\TodoAdded;
use ToDo\Domain\Query\Handler\ListTodoHandler;
use ToDo\Domain\Query\ListTodo;
use ToDo\Domain\ToDoRepository;
use ToDo\Infrastructure\Event\Listener\SendNewTodoToEmail;
use ToDo\Infrastructure\Repository\InMemoryTodoRepository;
use ToDo\Transport\FileReceiver;
use ToDo\Transport\FileSender;

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

$c['command_bus.middleware.logger'] = function ($c) {
    return new \ToDo\Middleware\LoggingMiddleware($c['command_logger']);
};

$c['command_bus'] = function ($c) {
    return new MessageBus(
        [
            $c['command_bus.middleware.logger'],
            new SendMessageMiddleware($c[SendersLocatorInterface::class]),
            new HandleMessageMiddleware($c['command_bus.handler_locator']),
        ]
    );
};

$c[AddNewToDoHandler::class] = function ($c) {
    return new AddNewToDoHandler($c[ToDoRepository::class], $c['event_bus']);
};

/*
 * Transport
 */

$c[FileSender::class] = function ($c) {
    return new FileSender(__DIR__.'/../todos');
};

$c['receiver.file'] = function ($c) {
    return new FileReceiver(__DIR__.'/../todos');
};

$c[SendersLocatorInterface::class] = function ($c) {
    return new SendersLocator([
        AddNewTodo::class => [FileSender::class => $c[FileSender::class]]
    ], [
        AddNewTodo::class => true
    ]);
};

/*
 * Query bus
 */
$c[ListTodoHandler::class] = function ($c) {
    return new ListTodoHandler($c[ToDoRepository::class]);
};

$c['query_bus.handler_locator'] = function ($c) {
    return new HandlersLocator([
        ListTodo::class => [ListTodoHandler::class => $c[ListTodoHandler::class]]
    ]);
};

$c['query_bus'] = function ($c) {
    return new MessageBus(
        [
            new HandleMessageMiddleware($c['query_bus.handler_locator']),
        ]
    );
};

/*
 * Event bus
 */
$c[SendNewTodoToEmail::class] = function ($c) {
    return new SendNewTodoToEmail();
};

$c['event_bus.handler_locator'] = function ($c) {
    return new HandlersLocator([
        TodoAdded::class => [SendNewTodoToEmail::class => $c[SendNewTodoToEmail::class]]
    ]);
};

$c['event_bus'] = function ($c) {
    return new MessageBus(
        [
            new HandleMessageMiddleware($c['event_bus.handler_locator'], true),
        ]
    );
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

/*
 * Logger
 */
$c['command_logger'] = function ($c) {
    $logger = new Logger('command');
    $logger->pushHandler($c['command_logger.file_handler']);

    return $logger;
};

$c['command_logger.file_handler'] = function ($c) {
    return new \Monolog\Handler\StreamHandler(__DIR__.'/../var/log/command.log');
};
