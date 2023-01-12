<?php
declare(strict_types=1);

namespace App\Application;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class InboundMessageHandler
{
    public function __invoke(InboundMessage $message): void
    {
        file_put_contents('./var/log/inbound.log', $message->toString() . PHP_EOL, FILE_APPEND);
    }
}