<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface StringElementBuilderInterface extends ScalarElementBuilderInterface
{
    public function notBlank(): static;
    public function pattern(?string $pattern): static;
    public function stripTags(bool $stripTags = true): static;
    public function trim(bool $trim = true): static;
    public function alnum(bool $isAlphaNumeric = true): static;
    public function length($min = null, $max = null): static;
    public function minLength(int $min): static;
    public function maxLength(int $max): static;
    public function noWhiteSpace(): static;
}
