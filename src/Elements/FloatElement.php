<?php


namespace CodexSoft\Transmission\Elements;

class FloatElement extends NumberElement
{
    protected ?array $acceptedPhpTypes = ['double', 'float', 'integer'];
    protected string $openApiType = 'number';

    /**
     * @param $data
     *
     * @return double|float|null
     * @inheritDoc
     */
    public function doNormalizeData($data): ?float
    {
        $data = parent::doNormalizeData($data);
        return (double) $data;
    }
}
