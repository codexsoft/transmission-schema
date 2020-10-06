<?php


namespace CodexSoft\TransmissionSchema;


use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationResult
{
    private $data;
    private array $extraData;
    private ConstraintViolationListInterface $violations;

    public function __construct($data, ConstraintViolationListInterface $violations, array $extraData = [])
    {
        $this->data = $data;
        $this->violations = $violations;
        $this->extraData = $extraData;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
