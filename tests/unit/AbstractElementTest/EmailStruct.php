<?php


namespace CodexSoft\TransmissionSchema\AbstractElementTest;


use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Contracts\JsonSchemaInterface;

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
