<?php


namespace CodexSoft\Transmission\Elements;

class IdElement extends IntegerElement
{
    protected $minValue = 0;
    protected bool $exclusiveMinimum = true;
}
