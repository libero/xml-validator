<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use DOMXPath;
use Libero\XmlValidator\Failure;
use Libero\XmlValidator\SchematronValidator;
use Libero\XmlValidator\ValidationFailed;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class SchematronValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider formatProvider
     */
    public function it_passes_on_valid(string $format) : void
    {
        $validator = new SchematronValidator(__DIR__."/fixtures/schema.{$format}");

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        $this->expectNotToPerformAssertions();

        $validator->validate($document);
    }

    /**
     * @test
     * @dataProvider formatProvider
     */
    public function it_fails_on_invalid(string $format) : void
    {
        $validator = new SchematronValidator(__DIR__."/fixtures/schema.{$format}");

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/invalid-empty-child.xml');
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('example', 'http://example.com');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [
                    new Failure(
                        'Must not be empty',
                        6,
                        $xpath->query('/example:parent/example:child')->item(0)
                    ),
                ],
                $e->getFailures()
            );
        }
    }

    public function formatProvider() : iterable
    {
        yield 'RELAX NG' => ['rng'];
        yield 'Schematron' => ['sch'];
        yield 'XML Schema' => ['xsd'];
    }

    /**
     * @test
     */
    public function it_fails_if_the_schema_does_not_exist() : void
    {
        $validator = new SchematronValidator(__DIR__.'/fixtures/not-a-schema.sch');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [new Failure('Failed to load \''.__DIR__.'/fixtures/not-a-schema.sch\'')],
                $e->getFailures()
            );
        }
    }

    /**
     * @test
     */
    public function it_fails_if_the_schema_is_not_a_schema() : void
    {
        $validator = new SchematronValidator(__DIR__.'/fixtures/valid.xml');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Schema appears not to be Schematron, an XSD, nor RNG: document element is '.
            '{http://example.com}parent');

        $validator->validate($document);
    }
}
