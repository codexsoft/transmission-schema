<?php


namespace CodexSoft\Transmission\Elements;

class BoolElement extends ScalarElement
{
    protected $example = true;
    protected string $openApiType = 'boolean';
    protected ?array $acceptedPhpTypes = ['bool'];

    /**
     * @param $data
     *
     * @return bool|null
     */
    protected function doNormalizeData($data): ?bool
    {
        $data = parent::doNormalizeData($data);
        return (bool) $data;
    }

    //public function toOpenApiV2(): array
    //{
    //    $data = parent::toOpenApiV2();
    //    return $data;
    //}
}
