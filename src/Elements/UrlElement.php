<?php


namespace CodexSoft\Transmission\Elements;

use Symfony\Component\Validator\Constraints;

class UrlElement extends StringElement
{
    protected $example = 'https://example.com';
    protected ?int $maxLength = 2048;

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Url();
        return $constraints;
    }
}
