<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface BasicElementBuilderInterface extends AbstractElementBuilderInterface
{
    /**
     * Set that element is optional.
     * Often, if optional element is missing in input data, it is replacing with some default value.
     *
     * @param string $defaultValue default value to be set if element is missing
     *
     * @return static
     */
    public function optional($defaultValue = BasicElement::UNDEFINED): static;

    /**
     * Set element value example
     *
     * @param mixed $exampleValue
     *
     * @return static
     */
    public function example($exampleValue): static;

    /**
     * Set element default value (default value will be applied if element is missing in input data)
     *
     * @param mixed $value
     *
     * @return static
     */
    public function defaultValue($value): static;

    /**
     * Set element accepted PHP types.
     * If value type from input data is outside these types, the exception will be generated
     * while validating or normalizing input data.
     *
     * @param string[] ...$acceptedTypes
     *
     * @return static
     */
    public function type(...$acceptedTypes): static;

    /**
     * @param bool $value enable or disable strict type checks (is disabled by default)
     *
     * @return static
     */
    public function strict(bool $value = true): static;
}
