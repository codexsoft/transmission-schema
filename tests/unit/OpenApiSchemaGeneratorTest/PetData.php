<?php


namespace CodexSoft\Transmission\OpenApiSchemaGeneratorTest;


use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\JsonSchemaInterface;

class PetData implements JsonSchemaInterface
{
    /**
     * @inheritDoc
     */
    public static function createSchema(): array
    {
        return [
            'id' => Accept::id('ID питомца'),
            'name' => Accept::string()->notBlank(),
        ];
    }
}
