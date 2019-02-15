<?php

namespace ToDo\Transport;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use ToDo\Domain\Command\AddNewTodo;

class FileReceiver implements ReceiverInterface
{
    private $shouldStop = false;

    /**
     * @var string
     */
    private $filePath;

    /**
     * FileReceiver constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Receive some messages to the given handler.
     *
     * The handler will have, as argument, the received {@link \Symfony\Component\Messenger\Envelope} containing the message.
     * Note that this envelope can be `null` if the timeout to receive something has expired.
     */
    public function receive(callable $handler): void
    {
        $file = fopen($this->filePath, 'r');

        while (!$this->shouldStop) {
            while (($data = fgetcsv($file)) !== false) {
                try {
                    $deadline = new \DateTimeImmutable($data[3]);
                } catch (\Exception $e) {
                    $deadline = null;
                }

                $addTodo = new AddNewTodo(Uuid::fromString($data[0]), $data[1], $data[2] === 'Y', $deadline);
                $handler(new Envelope($addTodo));
            }
            sleep(1);
        }

        fclose($file);
    }

    /**
     * Stop receiving some messages.
     */
    public function stop(): void
    {
        $this->shouldStop = true;
    }
}
