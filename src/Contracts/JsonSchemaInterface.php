<?php


namespace CodexSoft\TransmissionSchema\Contracts;


use CodexSoft\TransmissionSchema\Elements\AbstractElement;

interface JsonSchemaInterface
{
    /**
     * @return AbstractElement[]
     */
    public static function createSchema(): array;
}
