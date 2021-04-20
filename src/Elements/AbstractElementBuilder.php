<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * @deprecated
 */
abstract class AbstractElementBuilder extends AbstractBaseElementBuilder
{
    //use AbstractElementBuilderTrait;

    public function __construct(AbstractElement $element)
    {
        $this->element = $element;
    }

    /**
     * Set that element is optional.
     * Often, if optional element is missing in input data, it is replacing with some default value.
     *
     * @param string $defaultValue default value to be set if element is missing
     *
     * @return static
     */
    public function optional($defaultValue = AbstractElement::UNDEFINED)
    {
        $this->element->isRequired = false;

        if ($defaultValue !== AbstractElement::UNDEFINED) {
            $this->element->defaultValue = $defaultValue;
        }

        return $this;
    }

    /**
     * Set element value example
     *
     * @param mixed $exampleValue
     *
     * @return static
     */
    public function example($exampleValue)
    {
        $this->element->example = $exampleValue;
        return $this;
    }

    /**
     * Set element default value (default value will be applied if element is missing in input data)
     *
     * @param mixed $value
     *
     * @return static
     */
    public function defaultValue($value)
    {
        $this->element->defaultValue = $value;
        return $this;
    }

    /**
     * Set element accepted PHP types.
     * If value type from input data is outside these types, the exception will be generated
     * while validating or normalizing input data.
     *
     * @param string[] ...$acceptedTypes
     *
     * @return static
     */
    public function type(...$acceptedTypes)
    {
        $this->element->acceptedPhpTypes = $acceptedTypes;
        return $this;
    }

    /**
     * @param bool $value enable or disable strict type checks (is disabled by default)
     *
     * @return static
     */
    public function strict(bool $value = true)
    {
        $this->element->strictTypeCheck = $value;
        return $this;
    }
}
