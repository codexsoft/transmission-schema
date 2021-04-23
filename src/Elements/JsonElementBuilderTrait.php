<?php


namespace CodexSoft\Transmission\Schema\Elements;


trait JsonElementBuilderTrait
{
    /**
     * If extra fields are denied then if they are present in input data violation will occured
     * @param bool $allowExtraFields todo: remove this parameter of refactor method
     *
     * @return static
     */
    public function denyExtraFields(bool $allowExtraFields = false): static
    {
        $this->mode = JsonElement::MODE_DENY_EXTRA_KEYS;
        return $this;
    }

    /**
     * Set mode for dealing with extra keys in input data
     * @param int $mode MUST be one of self::MODE_ constants
     *
     * @return static
     */
    public function mode(int $mode): static
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Extra keys will be leaved in normalized data (but without any normalization!)
     * @return static
     */
    public function modeLeave(): static
    {
        return $this->mode(JsonElement::MODE_LEAVE_EXTRA_KEYS);
    }

    /**
     * Extra keys are not allowed and violation will be produced for each of them while validation
     * @return static
     */
    public function modeDeny(): static
    {
        return $this->mode(JsonElement::MODE_DENY_EXTRA_KEYS);
    }

    /**
     * Extra keys will be just ignored
     * @return static
     */
    public function modeIgnore(): static
    {
        return $this->mode(JsonElement::MODE_IGNORE_EXTRA_KEYS);
    }

    /**
     * @param $valuePattern
     *
     * @return static
     */
    public function extraElementSchema(BasicElement $valuePattern): static
    {
        $this->extraElementSchema = $valuePattern;
        return $this;
    }
}
