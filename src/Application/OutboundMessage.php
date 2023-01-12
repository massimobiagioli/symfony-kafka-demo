<?php
declare(strict_types=1);

namespace App\Application;

readonly class OutboundMessage
{
    public function __construct(public string $type, public string $message)
    {
    }

    public function toString(): string
    {
        return $this->type . '::' . $this->message;
    }
}