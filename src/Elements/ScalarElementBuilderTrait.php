<?php


namespace CodexSoft\Transmission\Schema\Elements;


trait ScalarElementBuilderTrait
{
    /**
     * @param array $validChoices
     *
     * @return static
     */
    public function choices(array $validChoices): static
    {
        $this->choicesSourceArray = $validChoices;
        return $this;
    }

    /**
     * @param array $substitutes
     *
     * @return static
     */
    public function substitutes(array $substitutes): static
    {
        $this->substitutes = $substitutes;
        return $this;
    }
}
