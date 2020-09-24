<?php


namespace CodexSoft\Transmission\AbstractElementTest;


use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\JsonSchemaInterface;

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
