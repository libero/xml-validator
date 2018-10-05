<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use Libero\XmlValidator\ValidationFailure;
use PHPUnit\Framework\TestCase;

final class ValidationFailureTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_message() : void
    {
        $failure = new ValidationFailure('message', 123);

        $this->assertSame('message', $failure->getMessage());
    }

    /**
     * @test
     */
    public function it_has_a_line_number() : void
    {
        $failure = new ValidationFailure('message', 123);

        $this->assertSame(123, $failure->getLine());
    }
}
