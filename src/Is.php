<?php


namespace CodexSoft\Transmission\Schema;

use CodexSoft\Transmission\Schema\Elements\AbstractElementBuilderInterface;
use CodexSoft\Transmission\Schema\Elements\StringElement;

/**
 * Alias
 */
class Is extends Accept
{
    public static function test(): AbstractElementBuilderInterface
    {
        return new StringElement();
    }
}
