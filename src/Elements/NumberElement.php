<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\ScalarElement;
use Symfony\Component\Validator\Constraints;

class NumberElement extends ScalarElement
{
    protected $example = 42.5;
    protected $lessThanOrEqual = null;
    protected $lessThan = null;
    protected $greaterThanOrEqual = null;
    protected $greaterThan = null;

    ///**
    // * @param $data
    // *
    // * @return int
    // */
    //public function doNormalizeData($data)
    //{
    //    return $data;
    //}

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        if ($this->lessThan !== null) {
            $constraints[] = new Constraints\LessThan($this->lessThan);
        }
        if ($this->lessThanOrEqual !== null) {
            $constraints[] = new Constraints\LessThanOrEqual($this->lessThanOrEqual);
        }
        if ($this->greaterThan !== null) {
            $constraints[] = new Constraints\GreaterThan($this->greaterThan);
        }
        if ($this->greaterThanOrEqual !== null) {
            $constraints[] = new Constraints\GreaterThanOrEqual($this->greaterThanOrEqual);
        }
        return $constraints;
    }

    /**
     * @param int|float $value
     *
     * @return static
     */
    public function lt($value)
    {
        $this->lessThan = $value;
        return $this;
    }

    /**
     * @param int|float $value
     *
     * @return static
     */
    public function lte($value)
    {
        $this->lessThanOrEqual = $value;
        return $this;
    }

    /**
     * @param int|float $value
     *
     * @return static
     */
    public function gt($value)
    {
        $this->greaterThan = $value;
        return $this;
    }

    /**
     * @param int|float $value
     *
     * @return static
     */
    public function gte($value)
    {
        $this->greaterThanOrEqual = $value;
        return $this;
    }
}
