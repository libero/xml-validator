<?php

declare(strict_types=1);

namespace tests\Libero\XmlValidator;

use DOMNode;
use Libero\XmlValidator\Failure;
use PHPUnit\Framework\TestCase;

final class FailureTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_a_message() : void
    {
        $failure = new Failure('message', 123);

        $this->assertSame('message', $failure->getMessage());
    }

    /**
     * @test
     */
    public function it_may_have_line_number() : void
    {
        $with = new Failure('message', 123);
        $withOut = new Failure('message');

        $this->assertSame(123, $with->getLine());
        $this->assertNull($withOut->getLine());
    }

    /**
     * @test
     */
    public function it_may_have_a_node() : void
    {
        $with = new Failure('message', null, $node = new DOMNode());
        $withOut = new Failure('message');

        $this->assertSame($node, $with->getNode());
        $this->assertNull($withOut->getNode());
    }
}
