<?php
declare(strict_types=1);

namespace App\Presentation\CLI;

use App\Application\OutboundMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Sender extends Command
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:send-to-device')
            ->setDescription('Send command to device')
            ->setHelp('This command allows you to send command to device')
            ->addArgument('payload', InputArgument::REQUIRED, 'Message payload');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Send command to device');

        $payloadAsString = $input->getArgument('payload');

        $payload = json_decode($payloadAsString, true);

        $this->bus->dispatch(new OutboundMessage(
            type: $payload['type'],
            message: $payload['message'],
        ));

        $output->writeln('Command sent - Payload: ' . $payloadAsString);

        return Command::SUCCESS;
    }
}