<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class DateTimeElement extends StringElement
{
    protected $example = '2020-05-10 12:34:56';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\DateTime();
        return $constraints;
    }
}
