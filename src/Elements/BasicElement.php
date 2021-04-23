<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\ValidationResult;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

/**
 * Element is building block of data structures. Combine them using special element types: Json and
 * Collection.
 */
abstract class BasicElement extends AbstractElement implements BasicElementBuilderInterface
{
    use BasicElementBuilderTrait;

    public const UNDEFINED = 'UNDEFINED-4c970a6d-fe50-492e-ba0c-73a75fd2f2fd';

    /**
     * @var Constraint[]
     * todo: implement usage of this feature
     */
    protected array $customSfConstraints = [];

    protected mixed $example = self::UNDEFINED;

    /**
     * Default value of element. Is applied when element is optional and input data for element is
     * missing.
     * @var mixed
     */
    protected mixed $defaultValue = self::UNDEFINED;

    protected ?array $acceptedPhpTypes = null;

    /**
     * Input data must strictly have one of whitelisted types
     * @var bool
     */
    protected bool $strictTypeCheck = false;

    /** @var \Closure|null  */
    protected $normalizeDataCallback = null;

    protected string $openApiType = 'mixed';

    /**
     * @deprecated
     * @param string $class
     *
     * @return string
     * @throws \ReflectionException
     */
    protected function createRef(string $class): string
    {
        $reflection = new \ReflectionClass($class);
        return '#/components/schemas/'.$reflection->getShortName();
    }

    /**
     * @param $data
     *
     * @return mixed|null
     */
    public function normalizeData($data)
    {
        /**
         * it doest not make sense normalizing null
         */
        if ($data === null && $this->isNullable) {
            return null;
        }

        /**
         * type-speciefic data normalizing
         */
        $normalizedData = $this->doNormalizeData($data);

        if ($this->normalizeDataCallback instanceof \Closure) {
            $normalizedData = ($this->normalizeDataCallback)($normalizedData);
        }

        return $normalizedData;
    }

    protected function doNormalizeData(mixed $data)
    {
        return $data;
    }

    public function getExample(): mixed
    {
        return $this->example;
    }

    /**
     * @param Constraint[] $customSfConstraints
     *
     * @return static
     */
    public function sfConstraints(array $customSfConstraints): self
    {
        $this->customSfConstraints = $customSfConstraints;
        return $this;
    }

    protected function applySubstitutes($rawData)
    {
        return $rawData;
    }

    /**
     * @param $data
     *
     * @return ValidationResult
     */
    public function validateNormalizedData($data): ValidationResult
    {
        /**
         * substitutions should be made BEFORE formal validation
         */

        /**
         * First - check that input data has acceptable types
         */
        $validator = Validation::createValidator();
        $sfFormalConstraints = $this->compileToFormalSymfonyValidatorConstraint();
        $formalViolations = $validator->validate($data, $sfFormalConstraints);
        if ($formalViolations->count()) {
            return new ValidationResult($data, $formalViolations);
        }

        /**
         * If so, normalizing data (trim strings, convert booleans and so on) and validate it
         */
        $normalizedData = $this->normalizeData($data);

        $sfConstraints = $this->compileToSymfonyValidatorConstraint();
        $violations = $validator->validate($normalizedData, $sfConstraints);

        return new ValidationResult($normalizedData, $violations);
    }

    /**
     * Overrideable symfony constraints generator for element of specific type
     * @return Constraint[]
     */
    protected function generateSfConstraints(): array
    {
        return $this->generateFormalSfConstraints();
    }

    /**
     * Overrideable symfony constraints generator for element of speciefic type
     * @return Constraint[]
     */
    protected function generateFormalSfConstraints(): array
    {
        $constraints = [];

        if ($this->isNullable === false) {
            $constraints[] = new Constraints\NotNull();
        }

        if ($this->strictTypeCheck && $this->acceptedPhpTypes) {
            $constraints[] = new Constraints\Type(['type' => $this->acceptedPhpTypes]);
        }

        return $constraints;
    }

    /**
     * Generate symfony constraints for element to be used in Symfony Validator
     * @return Constraint|Constraint[]
     */
    public function compileToSymfonyValidatorConstraint()
    {
        $constraints = \array_merge($this->generateSfConstraints(), $this->customSfConstraints);

        return $this->isRequired ? $constraints : new Constraints\Optional($constraints);
    }

    /**
     * Generate symfony constraints for element to be used in Symfony Validator
     * @return Constraint|Constraint[]
     */
    public function compileToFormalSymfonyValidatorConstraint()
    {
        $constraints = $this->generateFormalSfConstraints();

        return $this->isRequired ? $constraints : new Constraints\Optional($constraints);
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Does element have defined default value or not
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->defaultValue !== self::UNDEFINED;
    }

    ///**
    // * Internal helper function to detect if $value is match allowed PHP types
    // * @param $value
    // * @param array $acceptedTypes
    // *
    // * @return bool
    // */
    //protected function valueHasType($value, array $acceptedTypes): bool
    //{
    //    foreach ($acceptedTypes as $type) {
    //        $type = strtolower($type);
    //        $type = 'boolean' === $type ? 'bool' : $type;
    //        $isFunction = 'is_'.$type;
    //        $ctypeFunction = 'ctype_'.$type;
    //
    //        if (\function_exists($isFunction) && $isFunction($value)) {
    //            return true;
    //        }
    //
    //        if (\function_exists($ctypeFunction) && $ctypeFunction($value)) {
    //            return true;
    //        }
    //
    //        if ($value instanceof $type) {
    //            return true;
    //        }
    //    }
    //
    //    return false;
    //}
}
