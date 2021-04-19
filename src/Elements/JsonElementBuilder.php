<?php


namespace CodexSoft\Transmission\Schema\Elements;


class JsonElementBuilder extends AbstractElementBuilder
{
    /**
     * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(?JsonElement $element = null)
    {
        $this->element = $element ?? new JsonElement();
    }

    /**
     * If extra fields are denied then if they are present in input data violation will occured
     * @param bool $allowExtraFields todo: remove this parameter of refactor method
     *
     * @return static
     */
    public function denyExtraFields(bool $allowExtraFields = false): self
    {
        $this->element->mode = JsonElement::MODE_DENY_EXTRA_KEYS;
        return $this;
    }

    /**
     * Set mode for dealing with extra keys in input data
     * @param int $mode MUST be one of self::MODE_ constants
     *
     * @return static
     */
    public function mode(int $mode): self
    {
        $this->element->mode = $mode;
        return $this;
    }

    /**
     * Extra keys will be leaved in normalized data (but without any normalization!)
     * @return static
     */
    public function modeLeave(): self
    {
        return $this->element->mode(JsonElement::MODE_LEAVE_EXTRA_KEYS);
    }

    /**
     * Extra keys are not allowed and violation will be produced for each of them while validation
     * @return static
     */
    public function modeDeny(): self
    {
        return $this->element->mode(JsonElement::MODE_DENY_EXTRA_KEYS);
    }

    /**
     * Extra keys will be just ignored
     * @return static
     */
    public function modeIgnore(): self
    {
        return $this->element->mode(JsonElement::MODE_IGNORE_EXTRA_KEYS);
    }

    /**
     * @param $valuePattern
     *
     * @return static
     */
    public function extraElementSchema(AbstractElement $valuePattern): self
    {
        $this->element->extraElementSchema = $valuePattern;
        return $this;
    }
}
