<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\IntegerElement;

class IdElement extends IntegerElement
{
    protected $minValue = 0;
    //protected bool $inclusiveMinimum = false;
    protected bool $exclusiveMinimum = true;
}
