<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface AbstractBaseElementBuilderInterface
{
    public function label(string $label): static;
    public function notNull(): static;
    public function nullable(): static;
    public function deprecated(bool $isDeprecated = true): static;
}
