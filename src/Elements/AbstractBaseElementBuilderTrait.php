<?php


namespace CodexSoft\Transmission\Schema\Elements;


trait AbstractBaseElementBuilderTrait
{
    /**
     * Set short text label for element
     * @param string $label
     *
     * @return static
     */
    public function label(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull(): static
    {
        $this->isNullable = false;
        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return static
     */
    public function deprecated(bool $isDeprecated = true): static
    {
        $this->isDeprecated = $isDeprecated;
        return $this;
    }

    /**
     * Set that element value CAN be null
     * @return static
     */
    public function nullable(): static
    {
        $this->isNullable = true;
        return $this;
    }
}
