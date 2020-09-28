<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\NumberElement;
use Symfony\Component\Validator\Constraints;

class FloatElement extends NumberElement
{
    protected ?array $acceptedTypes = ['double', 'float', 'integer'];
    /**
     * @param $data
     *
     * @return double|float|null
     */
    public function doNormalizeData($data): ?float
    {
        $data = parent::doNormalizeData($data);
        return (double) $data;
    }
}
