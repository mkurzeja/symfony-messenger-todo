<?php

namespace ToDo\Command;

use Pimple\Container;
use Psy\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use ToDo\Domain\Query\ListTodo;
use ToDo\Domain\ToDo;

class ListTodoCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $envelope Envelope
         */
        $envelope = $this->container['query_bus']->dispatch(new ListTodo());
        /**
         * @var $handledStamp HandledStamp
         */
        $handledStamp = $envelope->last(HandledStamp::class);
        $todos = $handledStamp->getResult();


        $table = new Table($output);
        $table->setHeaders(['UUID', 'Task', 'Deadline', 'Done']);

        $table->setRows(
            array_map(
                function (ToDo $toDo) {
                    return [
                        $toDo->getUuid()->toString(),
                        $toDo->getTask(),
                        $toDo->getDeadline() ? $toDo->getDeadline()->format('Y-m-d H:i') : '',
                        $toDo->isDone() ? 'Y' : 'N',
                    ];
                },
                $todos
            )
        );

        $table->render();
    }

    protected function configure()
    {
        $this->setName('todo:list');
        $this->setAliases(['l']);

        parent::configure();
    }
}
