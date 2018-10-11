<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;

final class DummyValidator implements XmlValidator
{
    private $failures;

    public function __construct(Failure ...$failures)
    {
        $this->failures = $failures;
    }

    public function validate(DOMDocument $document) : void
    {
        if ($this->failures) {
            throw new ValidationFailed($this->failures);
        }
    }
}
