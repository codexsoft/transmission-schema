<?php


namespace CodexSoft\Transmission\Schema\Contracts;


use CodexSoft\Transmission\Schema\Elements\BasicElement;

interface JsonEndpointInterface
{
    /**
     * Expected request JSON schema
     *
     * @return BasicElement[]
     */
    public static function bodyInputSchema(): array;
}
