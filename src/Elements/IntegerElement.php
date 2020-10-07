<?php


namespace CodexSoft\Transmission\Schema\Elements;

class IntegerElement extends NumberElement
{
    protected ?array $acceptedPhpTypes = ['integer'];
    protected string $openApiType = 'integer';
    protected $example = 42;

    /**
     * @param $data
     *
     * @return int
     * @inheritDoc
     */
    public function doNormalizeData($data): ?int
    {
        $data = parent::doNormalizeData($data);
        return (int) $data;
    }
}
