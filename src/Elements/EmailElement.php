<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class EmailElement extends StringElement
{
    protected mixed $example = 'some@example.com';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Email();
        return $constraints;
    }
}
