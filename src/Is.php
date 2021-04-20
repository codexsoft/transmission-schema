<?php


namespace CodexSoft\Transmission\Schema;

use CodexSoft\Transmission\Schema\Elements\AbstractBaseElementBuilderInterface;
use CodexSoft\Transmission\Schema\Elements\StringElement;

/**
 * Alias
 */
class Is extends Accept
{
    public static function test(): AbstractBaseElementBuilderInterface
    {
        return new StringElement();
    }
}
