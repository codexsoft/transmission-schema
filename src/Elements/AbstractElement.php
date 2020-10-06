<?php


namespace CodexSoft\Transmission\Elements;


use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use CodexSoft\Transmission\ValidationResult;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;

abstract class AbstractElement
{
    public const UNDEFINED = 'UNDEFINED-4c970a6d-fe50-492e-ba0c-73a75fd2f2fd';

    /**
     * @var Constraint[]
     * todo: implement usage of this feature
     */
    protected array $customSfConstraints = [];

    protected string $description;

    /** @var mixed */
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

    /** @var \Closure|null  */
    protected $normalizeDataCallback = null;

    protected string $openApiType = 'mixed';

    public function toOpenApiV2ParameterArray(): array
    {
        $data = [
            'description' => $this->label,
            'type' => $this->openApiType,
            'required' => $this->isRequired,
        ];

        if ($this->example !== null) {
            $data['example'] = $this->example;
        }

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
         * todo: should failure prevent validation and collecting violations?
         */
        if ($this->strictTypeCheck && $data === null && !$this->isNullable) {
            throw new IncompatibleInputDataTypeException('NULL is not acceptable value');
        }

        /**
         * Check acceptable input data types in strict mode. Not acceptable generates failure.
         * todo: should failure prevent validation and collecting violations?
         */
        if ($this->strictTypeCheck && \is_array($this->acceptedPhpTypes) && $this->acceptedPhpTypes && !$this->valueHasType($data, $this->acceptedPhpTypes)) {
            throw new IncompatibleInputDataTypeException('Value must be one of accepted types: {'.\implode(', ', $this->acceptedPhpTypes).'} but '.\gettype($data).' given');
        }

        $normalizedData = $this->doNormalizeData($data);

        if ($this->normalizeDataCallback instanceof \Closure) {
            $normalizedData = ($this->normalizeDataCallback)($normalizedData);
        }

        return $normalizedData;
    }

    protected function doNormalizeData($data)
    {
        return $data;
    }

    /**
     * Set element description
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
     * Set that element is optional
     * @return static
     */
    public function optional()
    {
        $this->isRequired = false;
        return $this;
    }

    /**
     * Overrideable symfony constraints generator for element of speciefic type
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
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull()
    {
        $this->isNullable = false;
        return $this;
    }

    /**
     * Set that element value CAN be null
     * @return static
     */
    public function nullable()
    {
        $this->isNullable = true;
        return $this;
    }

    /**
     * Set element value example
     *
     * @param mixed $exampleValue
     *
     * @return static
     */
    public function example($exampleValue)
    {
        $this->example = $exampleValue;
        return $this;
    }

    /**
     * Set element default value (default value will be applied if element is missing in input data)
     *
     * @param mixed $value
     *
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

    /**
     * Does element have defined default value or not
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->defaultValue !== self::UNDEFINED;
    }

    /**
     * Set element accepted PHP types.
     * If value type from input data is outside these types, the exception will be generated
     * while validating or normalizing input data.
     *
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
     * Set short text label for element
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
     * @param bool $value enable or disable strict type checks (is disabled by default)
     *
     * @return static
     */
    public function strict(bool $value = true)
    {
        $this->strictTypeCheck = $value;
        return $this;
    }

    /**
     * Internal helper function to detect if $value is match allowed PHP types
     * @param $value
     * @param array $acceptedTypes
     *
     * @return bool
     */
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
