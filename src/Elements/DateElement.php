<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class DateElement extends StringElement
{
    protected mixed $example = '2020-05-25';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Date();
        return $constraints;
    }
}
