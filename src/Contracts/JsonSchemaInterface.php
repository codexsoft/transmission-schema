<?php


namespace CodexSoft\Transmission\Schema\Contracts;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

interface JsonSchemaInterface
{
    /**
     * @return AbstractElement[]
     */
    public static function createSchema(): array;
}
