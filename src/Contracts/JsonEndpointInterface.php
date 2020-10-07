<?php


namespace CodexSoft\Transmission\Schema\Contracts;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

interface JsonEndpointInterface
{
    /**
     * Expected request JSON schema
     * @return AbstractElement[]
     */
    public static function bodyInputSchema(): array;
}
