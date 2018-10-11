<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use Libero\XmlValidator\Failure;
use Libero\XmlValidator\ValidationFailed;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

final class ValidationFailedTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_an_exception() : void
    {
        $failed = new ValidationFailed([]);

        $this->assertInstanceOf(UnexpectedValueException::class, $failed);
    }

    /**
     * @test
     */
    public function it_has_failures() : void
    {
        $failures = [new Failure('', 0)];

        $failed = new ValidationFailed($failures);

        $this->assertEquals($failures, $failed->getFailures());
    }
}
