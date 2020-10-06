<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

interface JsonEndpointInterface
{
    /**
     * Expected request JSON schema
     * @return AbstractElement[]
     */
    public static function bodyInputSchema(): array;
}
