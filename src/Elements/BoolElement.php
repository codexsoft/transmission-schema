<?php


namespace CodexSoft\Transmission\Schema\Elements;

class BoolElement extends ScalarElement
{
    protected $example = true;
    protected string $openApiType = 'boolean';
    protected ?array $acceptedPhpTypes = ['bool'];

    /**
     * @param mixed $data
     *
     * @return bool|null
     * @inheritDoc
     */
    protected function doNormalizeData($data): ?bool
    {
        $data = parent::doNormalizeData($data);
        return (bool) $data;
    }
}
