<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;

interface XmlValidator
{
    /**
     * @throws ValidationFailed
     */
    public function validate(DOMDocument $document) : void;
}
