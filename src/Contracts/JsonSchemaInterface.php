<?php


namespace CodexSoft\Transmission\Schema\Contracts;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\ElementBuilderInterface;

interface JsonSchemaInterface
{
    /**
     //* @return AbstractElement[]|ElementBuilderInterface[]
     * @return array<string, AbstractElement|ElementBuilderInterface>
     */
    public static function createSchema(): array;
}
