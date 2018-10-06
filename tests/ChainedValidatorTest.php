<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use Libero\XmlValidator\ChainedValidator;
use Libero\XmlValidator\DummyValidator;
use Libero\XmlValidator\ValidationFailed;
use Libero\XmlValidator\ValidationFailure;
use PHPUnit\Framework\TestCase;

final class ChainedValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_chains_validators_without_failures() : void
    {
        $validator = new ChainedValidator(new DummyValidator(), new DummyValidator());

        $this->expectNotToPerformAssertions();

        $validator->validate(new DOMDocument());
    }

    /**
     * @test
     */
    public function it_chains_validators_with_failures() : void
    {
        $failure1 = new ValidationFailure('failure 1', 1);
        $failure2 = new ValidationFailure('failure 2', 2);
        $failure3 = new ValidationFailure('failure 3', 3);

        $validator = new ChainedValidator(new DummyValidator($failure1, $failure2), new DummyValidator($failure3));

        try {
            $validator->validate(new DOMDocument());
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals([$failure1, $failure2, $failure3], $e->getFailures());
        }
    }

    /**
     * @test
     */
    public function it_chains_validators_with_mixed_results() : void
    {
        $failure1 = new ValidationFailure('failure 1', 1);
        $failure2 = new ValidationFailure('failure 2', 2);

        $validator = new ChainedValidator(
            new DummyValidator($failure1),
            new DummyValidator(),
            new DummyValidator($failure2)
        );

        try {
            $validator->validate(new DOMDocument());
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals([$failure1, $failure2], $e->getFailures());
        }
    }
}
