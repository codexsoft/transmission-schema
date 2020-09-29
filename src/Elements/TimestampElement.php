<?php


namespace CodexSoft\Transmission\Elements;

class TimestampElement extends IntegerElement
{
    protected $example = 1541508448;
    protected $minValue = 0;
    //protected bool $inclusiveMinimum = true;
    protected bool $exclusiveMinimum = false;
}
