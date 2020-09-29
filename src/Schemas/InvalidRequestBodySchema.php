<?php


namespace CodexSoft\Transmission\Schemas;


use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\JsonSchemaInterface;

class InvalidRequestBodySchema implements JsonSchemaInterface
{

    public static function createSchema(): array
    {
        return [
            'message' => Accept::string('Error message'),
            'data' => Accept::collection(Accept::string(), 'violations data'),
        ];
    }
}
