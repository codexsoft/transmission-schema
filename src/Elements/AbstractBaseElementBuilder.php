<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * @deprecated
 */
abstract class AbstractBaseElementBuilder implements ElementBuilderInterface
{
    protected AbstractBaseElement $element;

    final public function build(): AbstractBaseElement
    {
        return $this->element;
    }

    /**
     * Set short text label for element
     * @param string $label
     *
     * @return static
     */
    public function label(string $label): self
    {
        $this->element->label = $label;
        return $this;
    }

    /**
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull(): self
    {
        $this->element->isNullable = false;
        return $this;
    }

    /**
     * Set that element value CAN be null
     * @return static
     */
    public function nullable()
    {
        $this->element->isNullable = true;
        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return static
     */
    public function deprecated(bool $isDeprecated = true): self
    {
        $this->element->isDeprecated = $isDeprecated;
        return $this;
    }
}
