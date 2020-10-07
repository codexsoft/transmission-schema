<?php


namespace CodexSoft\Transmission\Schema\Elements;

class IdElement extends IntegerElement
{
    protected $minValue = 0;
    protected bool $exclusiveMinimum = true;
}
