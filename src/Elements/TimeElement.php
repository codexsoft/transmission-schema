<?php


namespace CodexSoft\TransmissionSchema\Elements;

use Symfony\Component\Validator\Constraints;

class TimeElement extends StringElement
{
    protected $example = '12:34:56';

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        $constraints[] = new Constraints\Time();
        return $constraints;
    }
}
