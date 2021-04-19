<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Elements\AbstractBaseElement;

trait AbstractBaseElementBuilderTrait2
{
    protected AbstractBaseElement $element;

    /**
     * Set short text label for element
     * @param string $label
     *
     * @return static
     */
    public function label(string $label): self
    {
        //$this->element->setLabel($label);
        $this->element->label = $label;
        return $this;
    }

    final public function build(): AbstractBaseElement
    {
        return $this->element;
    }

    /**
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull(): self
    {
        //$this->element->setIsNullable(false);
        $this->element->isNullable = false;
        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return static
     */
    public function deprecated(bool $isDeprecated = true): self
    {
        //$this->element->setIsDeprecated($isDeprecated);
        $this->element->isDeprecated = $isDeprecated;
        return $this;
    }
}
