<?php


namespace CodexSoft\Transmission\Schema\Converters;


class OpenApiConvertFactory
{
    protected $references = [];

    /**
     * @param string $class
     *
     * @return string
     * @throws \ReflectionException
     */
    public function createRef(string $class): string
    {
        $reflection = new \ReflectionClass($class);
        return '#/components/schemas/'.$reflection->getShortName();
    }
}
