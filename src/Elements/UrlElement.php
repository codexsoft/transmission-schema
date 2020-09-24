<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\StringElement;
use Symfony\Component\Validator\Constraints;

class UrlElement extends StringElement
{
    protected $example = 'https://example.com';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Url();
        return $constraints;
    }
}
