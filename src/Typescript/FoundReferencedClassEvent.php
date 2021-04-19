<?php


namespace CodexSoft\Transmission\Schema\Typescript;


class FoundReferencedClassEvent
{
    public function __construct(private string $schemaClass, private string $tsName)
    {
    }

    /**
     * @return string
     */
    public function getSchemaClass(): string
    {
        return $this->schemaClass;
    }

    /**
     * @return string
     */
    public function getTsName(): string
    {
        return $this->tsName;
    }
}
