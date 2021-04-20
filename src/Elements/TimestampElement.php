<?php


namespace CodexSoft\Transmission\Schema\Elements;

class TimestampElement extends IntegerElement
{
    protected mixed $example = 1541508448;
    protected $minValue = 0;
    protected bool $exclusiveMinimum = false;
}
