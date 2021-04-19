<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;
use CodexSoft\Transmission\Schema\Exceptions\InvalidCollectionElementSchemaException;
use CodexSoft\Transmission\Schema\Exceptions\InvalidJsonSchemaException;

class CollectionElementBuilder extends AbstractElementBuilder
{
    /**
     * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(?CollectionElement $element = null)
    {
        $this->element = $element ?? new CollectionElement();
    }

    /**
     * @param AbstractElement|string|null $elementSchema
     *
     * @return static
     * @throws InvalidCollectionElementSchemaException
     */
    public function each($elementSchema): self
    {
        if (\is_string($elementSchema)) {
            $schemaClass = $elementSchema;
            if (!\class_exists($schemaClass) || !\in_array(JsonSchemaInterface::class, class_implements($schemaClass), true)) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass does not implement ".JsonSchemaInterface::class);
            }
            /** @var JsonSchemaInterface $schemaClass */
            try {
                $this->element->elementSchema = new JsonElement($schemaClass::createSchema());
            } catch (InvalidJsonSchemaException $e) {
                throw new InvalidCollectionElementSchemaException("Element schema class $schemaClass contains invalid schema");
            }
            $this->element->schemaGatheredFromClass = $schemaClass;
        } elseif ($elementSchema instanceof AbstractElement) {
            $this->element->elementSchema = $elementSchema;
            $this->element->schemaGatheredFromClass = null;
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
    public function unique(bool $elementsMustBeUnique = true)
    {
        $this->element->elementsMustBeUnique = $elementsMustBeUnique;
        return $this;
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
        $this->element->minCount = $min;
        $this->element->maxCount = $max;
        return $this;
    }

    /**
     * @param int $min
     *
     * @return static
     */
    public function minCount(int $min)
    {
        $this->element->minCount = $min;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return static
     */
    public function maxCount(int $max): self
    {
        $this->element->maxCount = $max;
        return $this;
    }
}
