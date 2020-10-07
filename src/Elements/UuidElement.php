<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class UuidElement extends StringElement
{
    protected $example = 'a8d8f871-481f-436f-b22f-6598f89635ca';
    protected ?int $minLength = 36;
    protected ?int $maxLength = 36;

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Uuid();
        return $constraints;
    }
}
