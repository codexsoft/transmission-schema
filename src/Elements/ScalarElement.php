<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Elements\AbstractElement;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use Symfony\Component\Validator\Constraints;

class ScalarElement extends AbstractElement
{
    protected $example = 'value';
    protected array $choicesSourceArray = [];

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();
        if ($this->choicesSourceArray) {
            $constraints[] = new Constraints\Choice(['choices' => \array_values($this->choicesSourceArray)]);
        }

        return $constraints;
    }

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

    protected function doValidate($data)
    {
        if (!\is_scalar($data)) {
            $this->reportViolation("$data must be scalar");
        }

        if ($this->choicesSourceArray && !\in_array($data, $this->choicesSourceArray)) {
            $this->reportViolation("Not acceptable value $data");
        }
    }
}
