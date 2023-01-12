<?php

namespace App\Infrastructure\Messenger;

use App\Application\InboundMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class InboundMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $record = json_decode($encodedEnvelope['body'], true);
        if (!$record) {
            return new Envelope(new InboundMessage(
                type: 'unknown',
                message: 'unknown',
            ));
        }

        return new Envelope(new InboundMessage(
            type: $record['type'],
            message: $record['message'],
        ));
    }

    public function encode(Envelope $envelope): array
    {
        /** @var InboundMessage $event */
        $event = $envelope->getMessage();

        return [
            'body' => json_encode([
                'type' => $event->type,
                'message' => $event->message,
            ]),
        ];
    }
}
