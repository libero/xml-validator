<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMNode;
use LibXMLError;
use function trim;

final class Failure
{
    private $message;
    private $line;
    private $node;

    public function __construct(string $message, ?int $line = null, ?DOMNode $node = null)
    {
        $this->message = $message;
        $this->line = $line;
        $this->node = $node;
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

    public function getNode() : ?DOMNode
    {
        return $this->node;
    }
}
