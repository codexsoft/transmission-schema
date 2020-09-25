<?php


namespace CodexSoft\Transmission\Elements;

use Symfony\Component\Validator\Constraints;

class DateElement extends StringElement
{
    protected $example = '2020-05-25';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Date();
        return $constraints;
    }
}
