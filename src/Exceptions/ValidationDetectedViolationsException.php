<?php


namespace CodexSoft\Transmission\Exceptions;


use Throwable;

class ValidationDetectedViolationsException extends \Exception
{
    private array $violations;

    public function __construct(
        array $violations,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
