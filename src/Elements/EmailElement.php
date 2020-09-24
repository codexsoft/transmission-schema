<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\StringElement;
use Symfony\Component\Validator\Constraints;

class EmailElement extends StringElement
{
    protected $example = 'some@example.com';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Email();
        return $constraints;
    }
}
