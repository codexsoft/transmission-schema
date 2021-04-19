<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Elements\AbstractBaseElement;

trait AbstractBaseElementBuilderTrait
{
    /**
     * Set short text label for element
     * @param string $label
     *
     * @return static
     */
    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull()
    {
        $this->isNullable = false;
        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return static
     */
    public function deprecated(bool $isDeprecated = true): self
    {
        $this->isDeprecated = $isDeprecated;
        return $this;
    }
}
