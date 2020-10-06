<?php


namespace CodexSoft\TransmissionSchema\Contracts;


use CodexSoft\TransmissionSchema\Elements\AbstractElement;

interface JsonEndpointInterface
{
    /**
     * Expected request JSON schema
     * @return AbstractElement[]
     */
    public static function bodyInputSchema(): array;
}
