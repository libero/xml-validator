<?php

declare(strict_types=1);

namespace Libero\XmlValidator;

use Throwable;
use UnexpectedValueException;

class ValidationFailed extends UnexpectedValueException
{
    private $failures;

    /**
     * @param Failure[] $failures
     */
    public function __construct(array $failures, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->failures = $failures;
    }

    /**
     * @return Failure[]
     */
    public function getFailures() : array
    {
        return $this->failures;
    }
}
