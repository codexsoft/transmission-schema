<?php


namespace CodexSoft\Transmission\Schema\AbstractElementTest;


use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;

class EmailStruct implements JsonSchemaInterface
{

    /**
     * @inheritDoc
     */
    public static function createSchema(): array
    {
        return [
            'message' => Accept::text(),
            'title' => Accept::text(),
            'recipients' => Accept::collection(Accept::text()),
        ];
    }
}
