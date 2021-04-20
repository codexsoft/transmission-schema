<?php


namespace CodexSoft\Transmission\Schema\Elements;

class FloatElement extends NumberElement
{
    protected ?array $acceptedPhpTypes = ['double', 'float', 'integer'];
    protected string $openApiType = 'number';

    /**
     * @param $data
     *
     * @return double|float|null
     */
    public function doNormalizeData(mixed $data): ?float
    {
        $data = parent::doNormalizeData($data);
        return (double) $data;
    }
}
