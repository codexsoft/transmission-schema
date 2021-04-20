<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class UrlElement extends StringElement
{
    protected mixed $example = 'https://example.com';
    protected ?int $maxLength = 2048;

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Url();
        return $constraints;
    }
}
