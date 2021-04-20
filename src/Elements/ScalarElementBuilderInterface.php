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
     * @param array $substitutes
     *
     * @return static
     */
    public function substitutes(array $substitutes): static;
}
