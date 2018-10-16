<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;

final class XmlSchemaValidator implements XmlValidator
{
    use LibXmlValidator;

    private $schema;

    public function __construct(string $schema)
    {
        $this->schema = $schema;
    }

    protected function doValidation(DOMDocument $document) : void
    {
        @$document->schemaValidate($this->schema);
    }
}
