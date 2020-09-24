<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\NumberElement;
use Symfony\Component\Validator\Constraints;

class IntegerElement extends NumberElement
{
    protected ?array $acceptedTypes = ['integer'];
    protected $example = 42;

    /**
     * @param $data
     *
     * @return int
     */
    public function doNormalizeData($data): ?int
    {
        return (int) $data;
    }
}
