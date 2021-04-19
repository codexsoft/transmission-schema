<?php


namespace CodexSoft\Transmission\Schema\Elements;


class NumberElementBuilder extends ScalarElementBuilder
{
    /**
     * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(?NumberElement $element = null)
    {
        $this->element = $element ?? new NumberElement();
    }

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lt($maxValue)
    {
        $this->element->maxValue = $maxValue;
        $this->element->exclusiveMaximum = true;
        return $this;
    }

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lte($maxValue)
    {
        $this->element->maxValue = $maxValue;
        $this->element->exclusiveMaximum = false;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gt($minValue)
    {
        $this->element->minValue = $minValue;
        $this->element->exclusiveMinimum = true;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gte($minValue)
    {
        $this->element->minValue = $minValue;
        $this->element->exclusiveMinimum = false;
        return $this;
    }
}
