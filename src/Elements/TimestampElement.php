<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\IntegerElement;
use Symfony\Component\Validator\Constraints;

class TimestampElement extends IntegerElement
{
    protected $example = 1541508448;
    protected $greaterThanOrEqual = 0;
}
