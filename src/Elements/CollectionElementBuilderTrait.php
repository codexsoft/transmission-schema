<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;
use CodexSoft\Transmission\Schema\Exceptions\InvalidCollectionElementSchemaException;
use CodexSoft\Transmission\Schema\Exceptions\InvalidJsonSchemaException;

trait CollectionElementBuilderTrait
{
    /**
     * @param AbstractElement|string|null $elementSchema
     *
     * @return static
     * @throws InvalidCollectionElementSchemaException
     */
    public function each($elementSchema): static
    {
        if (\is_string($elementSchema)) {
            $schemaClass = $elementSchema;
            if (!\class_exists($schemaClass) || !\in_array(JsonSchemaInterface::class, class_implements($schemaClass), true)) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass does not implement ".JsonSchemaInterface::class);
            }
            /** @var JsonSchemaInterface $schemaClass */
            try {
                //$this->element->elementSchema = new JsonElement($schemaClass::createSchema());
                $this->elementSchema = BuilderToElementConverter::normalizeToJsonElement($schemaClass::createSchema());
            } catch (InvalidJsonSchemaException $e) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass contains invalid schema");
            }
            $this->schemaGatheredFromClass = $schemaClass;
        } elseif ($elementSchema instanceof AbstractElement) {
            $this->elementSchema = $elementSchema;
            $this->schemaGatheredFromClass = null;
        } else {
            throw new InvalidCollectionElementSchemaException('Collection element schema must be object of '.AbstractElement::class.' or class implementing '.JsonSchemaInterface::class);
        }

        return $this;
    }

    /**
     * @param bool $elementsMustBeUnique
     *
     * @return static
     */
    public function unique(bool $elementsMustBeUnique = true): static
    {
        $this->elementsMustBeUnique = $elementsMustBeUnique;
        return $this;
    }

    /**
     * @param int|null $min
     *
     * @param int|null $max
     *
     * @return static
     */
    public function count(?int $min = null, ?int $max = null): static
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
     * @return static
     */
    public function maxCount(int $max): static
    {
        $this->maxCount = $max;
        return $this;
    }
}
