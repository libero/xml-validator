<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use DOMDocument;
use DOMElement;
use DOMXPath;
use UnexpectedValueException;
use XSLTProcessor;
use function array_map;
use function iterator_to_array;
use function trim;
use const LIBXML_BIGLINES;

final class SchematronValidator implements XmlValidator
{
    private const LIB_PATH = __DIR__.'/../lib/schematron';

    private $schema;

    public function __construct(string $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @throws ValidationFailed
     */
    public function validate(DOMDocument $document) : void
    {
        $schema = new DOMDocument();
        $foundSchema = @$schema->load($this->schema, LIBXML_BIGLINES);

        if (!$foundSchema) {
            throw new ValidationFailed([new Failure("Failed to load '{$this->schema}'")]);
        }

        $processor = new XSLTProcessor();

        $schematron = $this->toSchematron($processor, $schema);

        $validator = new DOMDocument();
        $validator->load(self::LIB_PATH.'/iso_svrl_for_xslt1.xsl', LIBXML_BIGLINES);

        $processor->importStylesheet($validator);
        $xslt = $processor->transformToDoc($schematron);

        $processor->importStylesheet($xslt);
        $result = $processor->transformToDoc($document);

        $resultXpath = new DOMXPath($result);
        $resultXpath->registerNamespace('svrl', 'http://purl.oclc.org/dsdl/svrl');
        $documentXpath = new DOMXPath($document);

        $failures = array_map(
            function (DOMElement $failedAssert) use ($documentXpath, $resultXpath) {
                $message = trim($resultXpath->query('svrl:text[1]', $failedAssert)->item(0)->textContent ?? '');
                $location = $documentXpath->query($failedAssert->getAttribute('location'))->item(0);

                return new Failure($message, $location ? $location->getLineNo() : null, $location);
            },
            iterator_to_array($resultXpath->query('/svrl:schematron-output/svrl:failed-assert'))
        );

        if (!empty($failures)) {
            throw new ValidationFailed($failures);
        }
    }

    private function toSchematron(XSLTProcessor $processor, DOMDocument $schema) : DOMDocument
    {
        $documentElement = "{{$schema->documentElement->namespaceURI}}{$schema->documentElement->nodeName}";

        if ('{http://purl.oclc.org/dsdl/schematron}schema' === $documentElement) {
            return $schema;
        }

        $extractor = new DOMDocument();
        $extractor->load($this->getExtractor($documentElement), LIBXML_BIGLINES);

        $processor->importStylesheet($extractor);

        return $processor->transformToDoc($schema);
    }

    private function getExtractor(string $documentElement) : string
    {
        switch ($documentElement) {
            case '{http://relaxng.org/ns/structure/1.0}grammar':
                return self::LIB_PATH.'/ExtractSchFromRNG.xsl';
            case '{http://www.w3.org/2001/XMLSchema}schema':
                return self::LIB_PATH.'/ExtractSchFromXSD.xsl';
        }

        throw new UnexpectedValueException(
            "Schema appears not to be Schematron, an XSD, nor RNG: document element is {$documentElement}"
        );
    }
}
