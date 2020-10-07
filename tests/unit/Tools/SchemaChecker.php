<?php


namespace CodexSoft\Transmission\Schema\Tools;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\JsonElement;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validation;

class SchemaChecker
{
    private AbstractElement $schema;
    private $expectedNormalizedData = AbstractElement::UNDEFINED;
    private $expectedExtraData = AbstractElement::UNDEFINED;
    private array $expectedSfViolations = [];

    public function __construct(AbstractElement $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param $data
     *
     * @return static
     */
    public function expectNormalizedData($data)
    {
        $this->expectedNormalizedData = $data;
        return $this;
    }

    /**
     * @param $data
     *
     * @return static
     */
    public function expectExtraData($data)
    {
        $this->expectedExtraData = $data;
        return $this;
    }

    /**
     * @param array $violations
     *
     * @return static
     */
    public function expectSfViolations(array $violations)
    {
        $this->expectedSfViolations = $violations;
        return $this;
    }

    public function check($data)
    {
        if ($this->schema instanceof JsonElement) {
            [$normalizedData, $extraData] = $this->schema->normalizeDataReturningNormalizedAndExtraData($data);

            if ($this->expectedExtraData !== AbstractElement::UNDEFINED && $extraData !== $this->expectedExtraData) {
                $n1 = $extraData;
                $n2 = $this->expectedExtraData;
                throw new \RuntimeException('Extra data is not same as expected');
            }

        } else {
            $normalizedData = $this->schema->normalizeData($data);
        }

        if ($this->expectedNormalizedData !== AbstractElement::UNDEFINED && $normalizedData !== $this->expectedNormalizedData) {
            $n1 = $normalizedData;
            $n2 = $this->expectedNormalizedData;
            throw new \RuntimeException('Normalized data is not same as expected');
        }

        $sfValidator = Validation::createValidator();
        $sfConstraint = $this->schema->compileToSymfonyValidatorConstraint();
        $sfViolations = $sfValidator->validate($normalizedData, $sfConstraint);

        if ($sfViolations->count() !== \count($this->expectedSfViolations)) {
            throw new \RuntimeException(\count($this->expectedSfViolations).' violations was expected but '.$sfViolations->count().' violations was detected!');
        }

        /** @var ConstraintViolationInterface $sfViolation */
        foreach ($sfViolations as $sfViolation) {
            if (
                !\array_key_exists($sfViolation->getPropertyPath(), $this->expectedSfViolations) ||
                $this->expectedSfViolations[$sfViolation->getPropertyPath()] !== $sfViolation->getMessage()
            ) {
                throw new \RuntimeException('Produced violation message for field '.$sfViolation->getPropertyPath().' was "'.$sfViolation->getMessage().'" but expected message was "'.$this->expectedSfViolations[$sfViolation->getPropertyPath()].'"');
            }
        }
    }
}
