<?php


namespace CodexSoft\TransmissionSchema\Elements;

class IdElement extends IntegerElement
{
    protected $minValue = 0;
    protected bool $exclusiveMinimum = true;
}
