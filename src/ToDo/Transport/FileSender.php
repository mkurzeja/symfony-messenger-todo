<?php

namespace ToDo\Transport;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use ToDo\Domain\Command\AddNewTodo;

class FileSender implements SenderInterface
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * Sends the given envelope.
     */
    public function send(Envelope $envelope): Envelope
    {
        $file = fopen($this->filePath, 'a');
        $message = $envelope->getMessage();

        if (!$message instanceof AddNewTodo) {
            return $envelope;
        }

        fputcsv(
            $file,
            [
                $message->uuid()->toString(),
                $message->task(),
                $message->deadline() ? $message->deadline()->format('Y-m-d') : '',
                $message->done() ? 'Y' : 'N',
            ]
        );
        fclose($file);

        return $envelope;
    }
}
