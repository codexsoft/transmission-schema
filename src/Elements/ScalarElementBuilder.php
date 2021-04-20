<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * @deprecated
 */
class ScalarElementBuilder extends AbstractBaseElementBuilder
{
    public function __construct(?ScalarElement $element = null)
    {
        $this->element = $element ?? new ScalarElement();
    }

    /**
     * @param array $validChoices
     *
     * @return static
     */
    public function choices(array $validChoices)
    {
        $this->element->choicesSourceArray = $validChoices;
        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function pattern(string $pattern)
    {
        $this->element->pattern = $pattern;
        return $this;
    }

    /**
     * @param array $substitutes
     *
     * @return static
     */
    public function substitutes(array $substitutes)
    {
        $this->element->substitutes = $substitutes;
        return $this;
    }
}
