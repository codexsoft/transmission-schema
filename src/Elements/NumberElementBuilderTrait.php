<?php


namespace CodexSoft\Transmission\Schema\Elements;


trait NumberElementBuilderTrait
{
    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lt($maxValue): static
    {
        $this->maxValue = $maxValue;
        $this->exclusiveMaximum = true;
        return $this;
    }

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lte($maxValue): static
    {
        $this->maxValue = $maxValue;
        $this->exclusiveMaximum = false;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gt($minValue): static
    {
        $this->minValue = $minValue;
        $this->exclusiveMinimum = true;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gte($minValue): static
    {
        $this->minValue = $minValue;
        $this->exclusiveMinimum = false;
        return $this;
    }
}
