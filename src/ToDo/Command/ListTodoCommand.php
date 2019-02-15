<?php

namespace ToDo\Command;

use Pimple\Container;
use Psy\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ToDo\Domain\ToDo;
use ToDo\Domain\ToDoRepository;

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
        $todos = $this->container[ToDoRepository::class]->findAll();

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
