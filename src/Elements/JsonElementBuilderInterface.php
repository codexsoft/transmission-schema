<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface JsonElementBuilderInterface extends BasicElementBuilderInterface
{
    /**
     * If extra fields are denied then if they are present in input data violation will occured
     * @param bool $allowExtraFields todo: remove this parameter of refactor method
     *
     * @return static
     */
    public function denyExtraFields(bool $allowExtraFields = false): static;

    /**
     * Set mode for dealing with extra keys in input data
     * @param int $mode MUST be one of self::MODE_ constants
     *
     * @return static
     */
    public function mode(int $mode): static;

    /**
     * Extra keys will be leaved in normalized data (but without any normalization!)
     * @return static
     */
    public function modeLeave(): static;

    /**
     * Extra keys are not allowed and violation will be produced for each of them while validation
     * @return static
     */
    public function modeDeny(): static;

    /**
     * Extra keys will be just ignored
     * @return static
     */
    public function modeIgnore(): static;

    /**
     * @param $valuePattern
     *
     * @return static
     */
    public function extraElementSchema(BasicElement $valuePattern): static;
}
