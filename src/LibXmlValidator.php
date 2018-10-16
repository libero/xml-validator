<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;
use function array_map;
use function count;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;

trait LibXmlValidator
{
    final public function validate(DOMDocument $document) : void
    {
        $internalErrors = libxml_use_internal_errors(true);
        libxml_clear_errors();

        @$this->doValidation($document);

        $errors = array_map([Failure::class, 'fromLibXmlError'], libxml_get_errors());

        libxml_clear_errors();
        libxml_use_internal_errors($internalErrors);

        if (count($errors) > 0) {
            throw new ValidationFailed($errors);
        }
    }

    abstract protected function doValidation(DOMDocument $document) : void;
}
