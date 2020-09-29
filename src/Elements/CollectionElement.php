<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use CodexSoft\Transmission\Exceptions\InvalidCollectionElementSchemaException;
use CodexSoft\Transmission\Exceptions\InvalidJsonSchemaException;
use CodexSoft\Transmission\JsonSchemaInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * Represents JSON array
 */
class CollectionElement extends AbstractElement
{
    protected ?array $acceptedTypes = ['array'];

    private ?AbstractElement $elementSchema = null;
    protected ?string $schemaGatheredFromClass = null;
    protected bool $strictTypeCheck = true;

    private ?int $minCount = null;
    private ?int $maxCount = null;
    private bool $elementsMustBeUnique = false;

    /**
     * @param AbstractElement|string|null $elementSchema
     *
     * @return $this
     * @throws InvalidCollectionElementSchemaException
     */
    public function each($elementSchema)
    {
        if (\is_string($elementSchema)) {
            $schemaClass = $elementSchema;
            if (!\class_exists($schemaClass) || !\in_array(JsonSchemaInterface::class, class_implements($schemaClass), true)) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass does not implement ".JsonSchemaInterface::class);
            }
            /** @var JsonSchemaInterface $schemaClass */
            try {
                $this->elementSchema = new JsonElement($schemaClass::createSchema());
            } catch (InvalidJsonSchemaException $e) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass contains invalid schema");
            }
            $this->schemaGatheredFromClass = $schemaClass;
        } elseif ($elementSchema instanceof AbstractElement) {
            $this->elementSchema = $elementSchema;
            $this->schemaGatheredFromClass = null;
        } else {
            throw new InvalidCollectionElementSchemaException('Collection element schema must be '.AbstractElement::class.' or class implementing '.JsonSchemaInterface::class);
        }

        return $this;
    }

    /**
     * @param bool $elementsMustBeUnique
     *
     * @return static
     */
    public function unique(bool $elementsMustBeUnique = true)
    {
        $this->elementsMustBeUnique = $elementsMustBeUnique;
        return $this;
    }

    /**
     * @param $data
     *
     * @return array|mixed
     * @throws IncompatibleInputDataTypeException
     */
    protected function doNormalizeData($data)
    {
        $normalizedData = [];
        if ($this->elementSchema) {
            foreach ($data as $datum) {
                $normalizedData[] = $this->elementSchema->normalizeData($datum);
            }
            return $normalizedData;
        }

        return $data;
    }

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();

        $countRestrictions = [];
        if ($this->minCount) {
            $countRestrictions['min'] = $this->minCount;
        }
        if ($this->maxCount) {
            $countRestrictions['max'] = $this->maxCount;
        }
        if ($countRestrictions) {
            $constraints[] = new Constraints\Count($countRestrictions);
        }

        if ($this->elementsMustBeUnique) {
            $constraints[] = new Constraints\Unique();
        }

        if ($this->elementSchema) {
            $compiledElementSchema = $this->elementSchema->compileToSymfonyValidatorConstraint();
            $constraints[] = new Constraints\All(['constraints' => $compiledElementSchema]);
        }

        return $constraints;
    }

    /**
     * @return Constraint|Constraint[]
     */
    public function compileToSymfonyValidatorConstraint()
    {
        //$constraints = new Constraints\Collection(\array_merge($this->generateSfConstraints(), $this->customSfConstraints));
        $constraints = \array_merge($this->generateSfConstraints(), $this->customSfConstraints);

        return $this->isRequired ? $constraints : new Constraints\Optional($constraints);
        //return $this->isRequired ? new Constraints\Required($constraints) : new Constraints\Optional($constraints);

        //if ($this->isRequired) {
        //    return new Constraints\Required($constraints);
        //}

        //return new Constraints\Optional($constraints);
    }

    /**
     * @param int|null $min
     *
     * @param int|null $max
     *
     * @return static
     */
    public function count(?int $min = null, ?int $max = null)
    {
        $this->minCount = $min;
        $this->maxCount = $max;
        return $this;
    }

    /**
     * @param int $min
     *
     * @return static
     */
    public function minCount(int $min)
    {
        $this->minCount = $min;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return $this
     */
    public function maxCount(int $max)
    {
        $this->maxCount = $max;
        return $this;
    }

    /**
     * @param $data
     *
     * @return mixed|void
     * @throws \CodexSoft\Transmission\Exceptions\ValidationDetectedViolationsException
     * @deprecated
     */
    protected function doValidate($data)
    {
        if (!\is_array($data)) {
            $this->reportViolation('Value must be array, '.\gettype($data).' given');
        }
    }
}
