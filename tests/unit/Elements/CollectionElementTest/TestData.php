<?php


namespace CodexSoft\TransmissionSchema\Elements\CollectionElementTest;


use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Contracts\JsonSchemaInterface;

class TestData implements JsonSchemaInterface
{
    public static function createSchema(): array
    {
        return [
            'a' => Accept::string(),
        ];
    }
}
