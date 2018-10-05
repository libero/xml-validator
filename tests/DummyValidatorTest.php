<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMDocument;
use Libero\XmlValidator\DummyValidator;
use Libero\XmlValidator\ValidationFailed;
use Libero\XmlValidator\ValidationFailure;
use PHPUnit\Framework\TestCase;

final class DummyValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_may_pass() : void
    {
        $validator = new DummyValidator();

        $this->expectNotToPerformAssertions();

        $validator->validate(new DOMDocument());
    }

    /**
     * @test
     */
    public function it_may_failure() : void
    {
        $failure1 = new ValidationFailure('failure 1', 1);
        $failure2 = new ValidationFailure('failure 2', 2);

        $validator = new DummyValidator($failure1, $failure2);

        try {
            $validator->validate(new DOMDocument());
            $this->fail('Validation passed but it should not have');
        } catch (ValidationFailed $e) {
            $this->assertEquals([$failure1, $failure2], $e->getFailures());
        }
    }
}
