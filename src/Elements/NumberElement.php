<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class NumberElement extends ScalarElement
{
    protected ?array $acceptedPhpTypes = ['integer', 'float', 'double'];
    protected $example = 42.5;

    protected $maxValue = null;
    protected $minValue = null;
    protected bool $exclusiveMaximum = true;
    protected bool $exclusiveMinimum = true;

    public function toOpenApiV2ParameterArray(): array
    {
        $data = parent::toOpenApiV2ParameterArray();

        if ($this->maxValue !== null) {
            $data['maximum'] = $this->maxValue;
            $data['exclusiveMaximum'] = $this->exclusiveMaximum;
        }

        if ($this->minValue !== null) {
            $data['minimum'] = $this->minValue;
            $data['exclusiveMinimum'] = $this->exclusiveMinimum;
        }

        return $data;
    }

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();

        if ($this->maxValue !== null) {
            if ($this->exclusiveMaximum) {
                $constraints[] = new Constraints\LessThan($this->maxValue);
            } else {
                $constraints[] = new Constraints\LessThanOrEqual($this->maxValue);
            }
        }

        if ($this->minValue !== null) {
            if ($this->exclusiveMinimum) {
                $constraints[] = new Constraints\GreaterThan($this->minValue);
            } else {
                $constraints[] = new Constraints\GreaterThanOrEqual($this->minValue);
            }
        }

        $constraints[] = new Constraints\Type(['type' => 'numeric']);

        return $constraints;
    }

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lt($maxValue)
    {
        $this->maxValue = $maxValue;
        $this->exclusiveMaximum = true;
        return $this;
    }

    /**
     * @param int|float $maxValue
     *
     * @return static
     */
    public function lte($maxValue)
    {
        $this->maxValue = $maxValue;
        $this->exclusiveMaximum = false;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gt($minValue)
    {
        $this->minValue = $minValue;
        $this->exclusiveMinimum = true;
        return $this;
    }

    /**
     * @param int|float $minValue
     *
     * @return static
     */
    public function gte($minValue)
    {
        $this->minValue = $minValue;
        $this->exclusiveMinimum = false;
        return $this;
    }
}
