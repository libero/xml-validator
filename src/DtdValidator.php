<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;

final class DtdValidator implements XmlValidator
{
    use LibXmlValidator;

    protected function doValidation(DOMDocument $document) : void
    {
        @$document->validate();
    }
}
