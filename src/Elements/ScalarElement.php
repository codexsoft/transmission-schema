<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use Symfony\Component\Validator\Constraints;

class ScalarElement extends AbstractElement
{
    protected $example = 'value';
    protected array $choicesSourceArray = [];
    protected ?string $pattern = null;

    public function toOpenApiV2Parameter(): array
    {
        $data = parent::toOpenApiV2Parameter();

        if ($this->pattern) {
            $data['pattern'] = $this->pattern;
        }

        if ($this->choicesSourceArray) {
            $data['enum'] = $this->choicesSourceArray;
        }

        return $data;
    }

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function pattern(string $pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        if ($this->choicesSourceArray) {
            $constraints[] = new Constraints\Choice(['choices' => \array_values($this->choicesSourceArray)]);
        }

        return $constraints;
    }

    /**
     * @param $data
     *
     * @return bool|float|int|mixed|string|null
     * @throws IncompatibleInputDataTypeException
     */
    protected function doNormalizeData($data)
    {
        $data = parent::doNormalizeData($data);

        if ($data !== null && !\is_scalar($data)) {
            throw new IncompatibleInputDataTypeException(\var_export($data, true).' is not scalar');
        }

        return $data;
    }

    /**
     * @param array $validChoices
     *
     * @return static
     */
    public function choices(array $validChoices)
    {
        $this->choicesSourceArray = $validChoices;
        return $this;
    }

    ///**
    // * @param $data
    // *
    // * @return mixed|void
    // * @throws \CodexSoft\Transmission\Exceptions\ValidationDetectedViolationsException
    // * @deprecated
    // */
    //protected function doValidate($data)
    //{
    //    if (!\is_scalar($data)) {
    //        $this->reportViolation("$data must be scalar");
    //    }
    //
    //    if ($this->choicesSourceArray && !\in_array($data, $this->choicesSourceArray)) {
    //        $this->reportViolation("Not acceptable value $data");
    //    }
    //}
}
