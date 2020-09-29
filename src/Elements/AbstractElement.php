<?php


namespace CodexSoft\Transmission\Elements;


use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use CodexSoft\Transmission\Exceptions\ValidationDetectedViolationsException;
use CodexSoft\Transmission\ValidationResult;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

abstract class AbstractElement
{
    public const UNDEFINED = 'UNDEFINED-4c970a6d-fe50-492e-ba0c-73a75fd2f2fd';

    /** @var Constraint[] */
    protected array $customSfConstraints = [];

    protected string $description;
    protected $example;
    protected string $label = '';
    protected ?string $defaultValue = self::UNDEFINED;

    protected bool $isRequired = true;
    protected bool $isNullable = false;
    protected ?array $acceptedPhpTypes = null;

    /**
     * Input data must strictly have one of whitelisted types
     * @var bool
     */
    protected bool $strictTypeCheck = false;

    /**
     * Should exception be thrown on first violation or not
     * @var bool
     */
    protected bool $stopOnFirstViolation = false;

    /**
     * Collected validation violations
     * @var array
     */
    protected array $violations = [];

    /** @var callable|null  */
    protected $normalizeDataCallback = null;

    protected string $openApiType = 'mixed';

    public function toOpenApiV2(): array
    {
        $data = [
            'example' => $this->example,
            'description' => $this->label,
            'type' => $this->openApiType,
        ];

        if ($this->hasDefaultValue()) {
            $data['default'] = $this->defaultValue;
        }

        return $data;
    }

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    public function process(?callable $callback)
    {
        $this->normalizeDataCallback = $callback;
        return $this;
    }

    /**
     * @param $data
     *
     * @return mixed|null
     * @throws IncompatibleInputDataTypeException
     */
    final public function normalizeData($data)
    {
        if ($data === null && $this->isNullable) {
            return null;
        }

        /**
         * NULL can be normalized to empty string or to 0. This is default behaviour.
         * To prevent this, strict type checking must be enabled.
         */
        if ($this->strictTypeCheck && $data === null && !$this->isNullable) {
            throw new IncompatibleInputDataTypeException('NULL is not acceptable value');
        }

        if ($this->strictTypeCheck && \is_array($this->acceptedPhpTypes) && $this->acceptedPhpTypes && !$this->valueHasType($data, $this->acceptedPhpTypes)) {
            throw new IncompatibleInputDataTypeException('Value must be one of accepted types: {'.\implode(', ', $this->acceptedPhpTypes).'} but '.\gettype($data).' given');
        }

        return $this->doNormalizeData($data);
    }

    protected function doNormalizeData($data)
    {
        return $data;
    }

    /**
     * @param string $description
     *
     * @return static
     */
    public function description(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $message
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected function reportViolation(string $message): void
    {
        $violation = $message;
        if ($this->stopOnFirstViolation) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new ValidationDetectedViolationsException([$violation]);
        }
        $this->violations[] = $violation;
    }

    //public function isValid(): bool
    //{
    //}

    /**
     * @param $data
     * @param bool $validateNormalizedData
     *
     * @return mixed|null
     * @throws IncompatibleInputDataTypeException
     * @throws ValidationDetectedViolationsException
     * @deprecated
     */
    final public function validateAndReturnData($data, bool $validateNormalizedData = true)
    {
        $normalizedData = $this->normalizeData($data);
        $this->doValidate($validateNormalizedData ? $normalizedData : $data);
        if ($this->violations) {
            throw new ValidationDetectedViolationsException($this->violations);
        }

        return $normalizedData;
    }

    /**
     * @param $data
     * @param bool $validateNormalizedData
     *
     * @return array
     * @throws IncompatibleInputDataTypeException
     * @deprecated
     */
    final public function validate($data, bool $validateNormalizedData = true)
    {
        $normalizedData = $this->normalizeData($data);

        try {
            $this->doValidate($validateNormalizedData ? $normalizedData : $data);
        } catch (ValidationDetectedViolationsException $e) {
            return $e->getViolations();
        }

        return $this->violations;
    }

    /**
     * @param $data
     *
     * @return ValidationResult
     * @throws IncompatibleInputDataTypeException
     */
    public function getValidatedNormalizedData($data): ValidationResult
    {
        $normalizedData = $this->normalizeData($data);

        $sfConstraints = $this->compileToSymfonyValidatorConstraint();
        $validator = Validation::createValidator();
        $violations = $validator->validate($normalizedData, $sfConstraints);

        return new ValidationResult($normalizedData, $violations);
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws ValidationDetectedViolationsException
     * @deprecated
     */
    abstract protected function doValidate($data);

    //public function required()
    //{
    //    $this->isRequired = true;
    //    return $this;
    //}

    public function optional()
    {
        $this->isRequired = false;
        return $this;
    }

    /**
     * @return Constraint[]
     */
    protected function generateSfConstraints(): array
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

    //public function compileToSymfonyValidatorConstraint(): Constraint

    /**
     * @return Constraint|Constraint[]
     */
    public function compileToSymfonyValidatorConstraint()
    {
        $constraints = \array_merge($this->generateSfConstraints(), $this->customSfConstraints);

        return $this->isRequired ? $constraints : new Constraints\Optional($constraints);
    }

    /**
     * @return static
     */
    public function notNull()
    {
        $this->isNullable = false;
        return $this;
    }

    /**
     * @return static
     */
    public function nullable()
    {
        $this->isNullable = true;
        return $this;
    }

    /**
     * @return static
     */
    public function example($exampleValue)
    {
        $this->example = $exampleValue;
        return $this;
    }

    /**
     * @return static
     */
    public function defaultValue($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue()
    {
        return $this->defaultValue !== self::UNDEFINED;
    }

    /**
     * @param string[] ...$acceptedTypes
     *
     * @return static
     */
    public function type(...$acceptedTypes)
    {
        $this->acceptedPhpTypes = $acceptedTypes;
        return $this;
    }

    /**
     * @param string $label
     *
     * @return static
     */
    public function label(string $label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return static
     */
    public function strict(bool $value = true)
    {
        $this->strictTypeCheck = $value;
        return $this;
    }

    protected function valueHasType($value, array $acceptedTypes): bool
    {
        foreach ($acceptedTypes as $type) {
            $type = strtolower($type);
            $type = 'boolean' === $type ? 'bool' : $type;
            $isFunction = 'is_'.$type;
            $ctypeFunction = 'ctype_'.$type;

            if (\function_exists($isFunction) && $isFunction($value)) {
                return true;
            }

            if (\function_exists($ctypeFunction) && $ctypeFunction($value)) {
                return true;
            }

            if ($value instanceof $type) {
                return true;
            }
        }

        return false;
    }
}
