<?php


namespace CodexSoft\Transmission\Elements\CollectionElementTest;


use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\JsonSchemaInterface;

class TestData implements JsonSchemaInterface
{
    public static function createSchema(): array
    {
        return [
            'a' => Accept::string(),
        ];
    }
}
