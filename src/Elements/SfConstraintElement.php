<?php


namespace CodexSoft\TransmissionSchema\Elements;

use Symfony\Component\Validator\Constraint;

class SfConstraintElement extends AbstractElement
{
    /** @var Constraint[] */
    protected array $customSfConstraints;

    /**
     * SfConstraintPart constructor.
     *
     * @param string $label
     * @param mixed Constraint[] ...$constraints
     */
    public function __construct(string $label = '', ...$constraints)
    {
        $this->customSfConstraints = $constraints;
        parent::__construct($label);
    }

    //public function compileToSymfonyValidatorConstraint()

    /**
     * @return Constraint|Constraint[]
     */
    public function compileToSymfonyValidatorConstraint()
    {
        return $this->customSfConstraints;
    }
}
