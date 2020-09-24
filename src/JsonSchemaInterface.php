<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

interface JsonSchemaInterface
{
    /**
     * @return AbstractElement[]
     */
    public static function createSchema(): array;
}
