<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\StringElement;

class UuidElement extends StringElement
{
    protected $example = 'a8d8f871-481f-436f-b22f-6598f89635ca';
    protected ?int $minLength = 36;
    protected ?int $maxLength = 36;
}
