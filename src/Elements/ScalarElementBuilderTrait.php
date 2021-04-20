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

    ///**
    // * @param string $pattern
    // *
    // * @return static
    // */
    //public function pattern(string $pattern): static
    //{
    //    $this->pattern = $pattern;
    //    return $this;
    //}

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
