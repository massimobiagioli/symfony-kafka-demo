<?php

namespace App\Infrastructure\Messenger;

use App\Application\OutboundMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class OutboundMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $record = json_decode($encodedEnvelope['body'], true);

        return new Envelope(new OutboundMessage(
            $record['type'],
            $record['message'],
        ));
    }

    public function encode(Envelope $envelope): array
    {
        /** @var OutboundMessage $event */
        $event = $envelope->getMessage();

        return [
            'body' => json_encode([
                'type' => $event->type,
                'message' => $event->message,
            ]),
        ];
    }
}
