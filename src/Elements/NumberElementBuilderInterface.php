<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface NumberElementBuilderInterface extends ScalarElementBuilderInterface
{
    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lt($maxValue): static;

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lte($maxValue): static;

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gt($minValue): static;

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gte($minValue): static;
}
