<?php


namespace CodexSoft\Transmission\Contracts;


use CodexSoft\Transmission\Elements\AbstractElement;

interface JsonSchemaInterface
{
    /**
     * @return AbstractElement[]
     */
    public static function createSchema(): array;
}
