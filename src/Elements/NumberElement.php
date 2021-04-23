<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class NumberElement extends ScalarElement implements NumberElementBuilderInterface
{
    use NumberElementBuilderTrait;

    protected ?array $acceptedPhpTypes = ['integer', 'float', 'double'];
    protected mixed $example = 42.5;

    protected $maxValue = null;
    protected $minValue = null;
    protected bool $exclusiveMaximum = true;
    protected bool $exclusiveMinimum = true;

    /**
     * @return null
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     * @return null
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     * @return bool
     */
    public function isExclusiveMaximum(): bool
    {
        return $this->exclusiveMaximum;
    }

    /**
     * @return bool
     */
    public function isExclusiveMinimum(): bool
    {
        return $this->exclusiveMinimum;
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

        return $constraints;
    }

    protected function generateFormalSfConstraints(): array
    {
        $constraints = parent::generateFormalSfConstraints();

        //if ($this->substitutes) {
        //    $constraints[] = new Constraints\AtLeastOneOf([
        //        new Constraints\Type(['type' => 'numeric']),
        //        new Constraints\Choice(['choices' => \array_keys($this->substitutes)]),
        //    ]);
        //} else {
        //    $constraints[] = new Constraints\Type(['type' => 'numeric']);
        //}

        $constraints[] = new Constraints\Type(['type' => 'numeric']);
        return $constraints;
    }

    ///**
    // * @param int|float $maxValue
    // *
    // * @return static
    // */
    //public function lt($maxValue)
    //{
    //    $this->maxValue = $maxValue;
    //    $this->exclusiveMaximum = true;
    //    return $this;
    //}
    //
    ///**
    // * @param int|float $maxValue
    // *
    // * @return static
    // */
    //public function lte($maxValue)
    //{
    //    $this->maxValue = $maxValue;
    //    $this->exclusiveMaximum = false;
    //    return $this;
    //}
    //
    ///**
    // * @param int|float $minValue
    // *
    // * @return static
    // */
    //public function gt($minValue)
    //{
    //    $this->minValue = $minValue;
    //    $this->exclusiveMinimum = true;
    //    return $this;
    //}
    //
    ///**
    // * @param int|float $minValue
    // *
    // * @return static
    // */
    //public function gte($minValue)
    //{
    //    $this->minValue = $minValue;
    //    $this->exclusiveMinimum = false;
    //    return $this;
    //}
}
