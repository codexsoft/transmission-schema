<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Exceptions\InvalidCollectionElementSchemaException;

interface CollectionElementBuilderInterface extends AbstractElementBuilderInterface
{
    /**
     * @param AbstractElement|string|null $elementSchema
     *
     * @return static
     * @throws InvalidCollectionElementSchemaException
     */
    public function each($elementSchema): static;

    /**
     * @param bool $elementsMustBeUnique
     *
     * @return static
     */
    public function unique(bool $elementsMustBeUnique = true): static;

    /**
     * @param int|null $min
     *
     * @param int|null $max
     *
     * @return static
     */
    public function count(?int $min = null, ?int $max = null): static;

    /**
     * @param int $min
     *
     * @return static
     */
    public function minCount(int $min): static;

    /**
     * @param int $max
     *
     * @return static
     */
    public function maxCount(int $max): static;
}
