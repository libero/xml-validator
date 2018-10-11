<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;
use function array_merge;

final class CompositeValidator implements XmlValidator
{
    private $validators;

    public function __construct(XmlValidator ...$validators)
    {
        $this->validators = $validators;
    }

    public function validate(DOMDocument $document) : void
    {
        $failures = [];

        foreach ($this->validators as $validator) {
            try {
                $validator->validate($document);
            } catch (ValidationFailed $e) {
                $failures = array_merge($failures, $e->getFailures());
            }
        }

        if (!empty($failures)) {
            throw new ValidationFailed($failures);
        }
    }
}
