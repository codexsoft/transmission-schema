<?php


namespace CodexSoft\Transmission\Schema\Elements;

class BoolElement extends ScalarElement
{
    protected mixed $example = true;
    protected ?array $acceptedPhpTypes = ['bool'];

    protected function doNormalizeData(mixed $data): ?bool
    {
        $data = parent::doNormalizeData($data);
        return (bool) $data;
    }
}
