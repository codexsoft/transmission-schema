<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface ScalarElementBuilderInterface extends AbstractElementBuilderInterface
{
    /**
     * @param array $validChoices
     *
     * @return static
     */
    public function choices(array $validChoices): static;

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function pattern(string $pattern): static;

    /**
     * @param array $substitutes
     *
     * @return static
     */
    public function substitutes(array $substitutes): static;
}
