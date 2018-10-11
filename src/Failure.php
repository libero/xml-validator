<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use LibXMLError;
use function trim;

final class Failure
{
    private $message;
    private $line;

    public function __construct(string $message, ?int $line = null)
    {
        $this->message = $message;
        $this->line = $line;
    }

    public static function fromLibXmlError(LibXMLError $error) : Failure
    {
        return new Failure(
            trim($error->message),
            $error->line ?? null
        );
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
