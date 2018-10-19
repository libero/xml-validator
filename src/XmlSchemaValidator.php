<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;

final class XmlSchemaValidator implements XmlValidator
{
    use LibXmlValidator;

    private $schemaPath;

    public function __construct(string $schemaPath)
    {
        $this->schemaPath = $schemaPath;
    }

    protected function doValidation(DOMDocument $document) : void
    {
        @$document->schemaValidate($this->schemaPath);
    }
}
