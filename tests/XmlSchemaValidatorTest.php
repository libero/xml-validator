<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use Libero\XmlValidator\Failure;
use Libero\XmlValidator\ValidationFailed;
use Libero\XmlValidator\XmlSchemaValidator;
use PHPUnit\Framework\TestCase;

final class XmlSchemaValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_passes_on_valid() : void
    {
        $validator = new XmlSchemaValidator(__DIR__.'/fixtures/schema.xsd');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        $this->expectNotToPerformAssertions();

        $validator->validate($document);
    }

    /**
     * @test
     */
    public function it_fails_on_invalid() : void
    {
        $validator = new XmlSchemaValidator(__DIR__.'/fixtures/schema.xsd');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/invalid-empty-parent.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [
                    new Failure(
                        'Element \'{http://example.com}parent\': Missing child element(s). Expected is'.
                        ' ( {http://example.com}child ).',
                        3
                    ),
                ],
                $e->getFailures()
            );
        }
    }

    /**
     * @test
     */
    public function it_fails_if_the_schema_does_not_exist() : void
    {
        $validator = new XmlSchemaValidator(__DIR__.'/fixtures/not-a-schema.xsd');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $xsd = __DIR__.'/fixtures/not-a-schema.xsd';
            $this->assertEquals(
                [
                    new Failure("failed to load external entity \"{$xsd}\""),
                    new Failure("Failed to locate the main schema resource at '{$xsd}'."),
                ],
                $e->getFailures()
            );
        }
    }
}
