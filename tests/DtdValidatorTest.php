<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use Libero\XmlValidator\DtdValidator;
use Libero\XmlValidator\Failure;
use Libero\XmlValidator\ValidationFailed;
use PHPUnit\Framework\TestCase;

final class DtdValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_passes_on_valid() : void
    {
        $validator = new DtdValidator();

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
        $validator = new DtdValidator();

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/invalid-empty-parent.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [new Failure('Element parent content does not follow the DTD, expecting (child)+, got', 4)],
                $e->getFailures()
            );
        }
    }

    /**
     * @test
     */
    public function it_fails_if_the_document_does_not_have_a_dtd() : void
    {
        $validator = new DtdValidator();

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/invalid-no-dtd.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [new Failure('no DTD found!')],
                $e->getFailures()
            );
        }
    }
}
