<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use Libero\XmlValidator\Failure;
use Libero\XmlValidator\RelaxNgValidator;
use Libero\XmlValidator\ValidationFailed;
use PHPUnit\Framework\TestCase;

final class RelaxNgValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_passes_on_valid() : void
    {
        $validator = new RelaxNgValidator(__DIR__.'/fixtures/schema.rng');

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
        $validator = new RelaxNgValidator(__DIR__.'/fixtures/schema.rng');

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/invalid-empty-parent.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [
                    new Failure('Expecting an element , got nothing', 3),
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
        $rng = __DIR__.'/fixtures/not-a-schema.rng';

        $validator = new RelaxNgValidator($rng);

        $document = new DOMDocument();
        $document->load(__DIR__.'/fixtures/valid.xml');

        try {
            $validator->validate($document);
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals(
                [
                    new Failure("failed to load external entity \"{$rng}\""),
                    new Failure("xmlRelaxNGParse: could not load {$rng}"),
                ],
                $e->getFailures()
            );
        }
    }
}
