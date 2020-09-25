<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\ScalarElement;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use Symfony\Component\Validator\Constraints;

class BoolElement extends ScalarElement
{
    protected $example = true;
    protected ?array $acceptedTypes = ['bool'];

    /**
     * @param $data
     *
     * @return bool|null
     */
    protected function doNormalizeData($data): ?bool
    {
        return (bool) $data;
    }
}
