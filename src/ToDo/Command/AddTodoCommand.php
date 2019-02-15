<?php

namespace ToDo\Command;

use Pimple\Container;
use Psy\Command\Command;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use ToDo\Domain\Command\AddNewTodo;

class AddTodoCommand extends Command
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
        $uuid = Uuid::uuid4();
        $helper = $this->getHelper('question');

        $question = new Question('Whats the task: ', 'Prepare messenger presentation');
        $task = $helper->ask($input, $output, $question);

        $question = new Question('Deadline (if exists): ', 'Prepare messenger presentation');
        $deadline = $helper->ask($input, $output, $question);

        try {
            $deadline = new \DateTimeImmutable($deadline);
        } catch (\Exception $e) {
            $deadline = null;
        }

        $addNewTodo = AddNewTodo::add($uuid, $task, $deadline);
        $this->container['command_bus']->dispatch($addNewTodo);

        $output->writeln('Task added!');
    }

    protected function configure()
    {
        $this->setName('todo:add');
        $this->setAliases(['a']);

        parent::configure();
    }
}
