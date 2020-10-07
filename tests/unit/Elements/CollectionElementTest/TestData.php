<?php


namespace CodexSoft\Transmission\Schema\Elements\CollectionElementTest;


use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;

class TestData implements JsonSchemaInterface
{
    public static function createSchema(): array
    {
        return [
            'a' => Accept::string(),
        ];
    }
}
