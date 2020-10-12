<?php


namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\ValidationResult;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

class ScalarElement extends AbstractElement
{
    protected $example = 'value';
    protected array $choicesSourceArray = [];
    protected ?string $pattern = null;

    /**
     * Raw input data can be replaced with substitutes array. For example: ['' => null, 'a' => 'b']
     * @var array
     */
    protected array $substitutes = [];

    /**
     * @param array $substitutes
     *
     * @return static
     */
    public function substitutes(array $substitutes)
    {
        $this->substitutes = $substitutes;
        return $this;
    }

    public function toOpenApiV2ParameterArray(): array
    {
        $data = parent::toOpenApiV2ParameterArray();

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
            $constraints[] = new Constraints\Choice([
                'choices' => \array_values($this->choicesSourceArray),
                'message' => 'The value you selected is not a valid choice. Accepted values are: {{ choices }}',
            ]);
        }

        return $constraints;
    }

    //final public function normalizeData($data)
    //{
    //    if ($this->substitutes && \in_array($data, $this->substitutes, true)) {
    //        return $this->substitutes[$data];
    //    }
    //
    //    return parent::normalizeData($data);
    //}

    /**
     * @param $data
     *
     * @return ValidationResult
     */
    public function validateNormalizedData($data): ValidationResult
    {
        if ($this->substitutes && \in_array($data, $this->substitutes, true)) {
            $data = $this->substitutes[$data];
        }

        return parent::validateNormalizedData($data);
    }

    protected function applySubstitutes($rawData)
    {
        if ($this->substitutes && \array_key_exists($rawData, $this->substitutes)) {
            return $this->substitutes[$rawData];
        }

        return $rawData;
    }

    ///**
    // * @param $data
    // *
    // * @return bool|float|int|mixed|string|null
    // */
    //protected function doNormalizeData($data)
    //{
    //    $data = parent::doNormalizeData($data);
    //
    //    if ($this->substitutes && \in_array($data, $this->substitutes, true)) {
    //        return $this->substitutes[$data];
    //    }
    //
    //    return $data;
    //}

    protected function generateFormalSfConstraints(): array
    {
        $constraints = parent::generateFormalSfConstraints();

        //if ($this->substitutes) {
        //    $constraints[] = new Constraints\AtLeastOneOf([
        //        new Constraints\Type(['type' => 'scalar']),
        //        new Constraints\Choice(['choices' => \array_keys($this->substitutes)]),
        //    ]);
        //} else {
        //    $constraints[] = new Constraints\Type(['type' => 'scalar']);
        //}

        $constraints[] = new Constraints\Type(['type' => 'scalar']);

        return $constraints;
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
}
