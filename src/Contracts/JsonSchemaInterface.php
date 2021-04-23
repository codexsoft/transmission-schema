<?php


namespace CodexSoft\Transmission\Schema\Contracts;


use CodexSoft\Transmission\Schema\Elements\BasicElement;

interface JsonSchemaInterface
{
    /**
     * @return BasicElement[]
     */
    public static function createSchema(): array;
}
