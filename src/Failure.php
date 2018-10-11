<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

final class Failure
{
    private $message;
    private $line;

    public function __construct(string $message, ?int $line = null)
    {
        $this->message = $message;
        $this->line = $line;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

    public function getLine() : ?int
    {
        return $this->line;
    }
}
