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
abstract class AbstractElement extends AbstractBaseElement
{
    public const UNDEFINED = 'UNDEFINED-4c970a6d-fe50-492e-ba0c-73a75fd2f2fd';

    /**
     * @var Constraint[]
     * todo: implement usage of this feature
     */
    protected array $customSfConstraints = [];

    /**
     * @deprecated use label instead
     * @var string
     */
    protected string $description = '';

    /** @var mixed */
    protected $example = self::UNDEFINED;

    /**
     * Default value of element. Is applied when element is optional and input data for element is
     * missing.
     * @var mixed
     */
    protected $defaultValue = self::UNDEFINED;

    protected ?array $acceptedPhpTypes = null;

    /**
     * Input data must strictly have one of whitelisted types
     * @var bool
     */
    protected bool $strictTypeCheck = false;

    /** @var \Closure|null  */
    protected $normalizeDataCallback = null;

    protected string $openApiType = 'mixed';

    //public function __construct(string $label = '')
    //{
    //    $this->label = $label;
    //}

    protected function createRef(string $class): string
    {
        $reflection = new \ReflectionClass($class);
        return '#/components/schemas/'.$reflection->getShortName();
    }

    /**
     * Export element to Parameter Object of OpenAPI 3.x
     *
     * @param string $name a name of parameter
     * @param string|null $in The location of the parameter. Possible values are "query", "header", "path" or "cookie". If omitted, it won't be added.
     *
     * @return array
     */
    public function toOpenApiParameter(string $name, ?string $in = null): array
    {
        $data = [
            'name' => $name,
            'schema' => $this->toOpenApiSchema(),
            'required' => $this->isRequired()
        ];

        if ($in !== null) {
            $data['in'] = $in;
        }

        return $data;
    }

    /**
     * Export element to Schema Object of OpenAPI 3.x (partially)
     *
     * @see https://json-schema.org/draft/2019-09/json-schema-core.html
     * @see https://json-schema.org/draft/2019-09/json-schema-core.html
     * @return array
     */
    public function toOpenApiSchema(): array
    {
        $data = [
            'description' => $this->label,
            'type' => $this->openApiType,
            'required' => $this->isRequired,
            'nullable' => $this->isNullable,
            'deprecated' => $this->isDeprecated,
        ];

        if ($this->example !== self::UNDEFINED) {
            $data['example'] = $this->example;
        }

        if ($this->hasDefaultValue()) {
            $data['default'] = $this->defaultValue;
        }

        return $data;
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

    protected function doNormalizeData($data)
    {
        return $data;
    }

    /**
     * @return mixed
     */
    public function getExample()
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
     * Set that element is optional.
     * Often, if optional element is missing in input data, it is replacing with some default value.
     *
     * @param string $defaultValue default value to be set if element is missing
     *
     * @return static
     */
    public function optional($defaultValue = self::UNDEFINED)
    {
        $this->isRequired = false;

        if ($defaultValue !== self::UNDEFINED) {
            $this->defaultValue($defaultValue);
        }

        return $this;
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

    /**
     * @deprecated use toOpenApiSchema instead
     * @return array
     */
    final public function toOpenApiV2ParameterArray(): array
    {
        return $this->toOpenApiSchema();
    }

    /**
     * callback to be applied to already normalized data
     * @param callable|null $callback
     *
     * @return static
     * @deprecated should not use, needs testing
     */
    public function process(?callable $callback): self
    {
        $this->normalizeDataCallback = $callback;
        return $this;
    }

    /**
     * @deprecated use label() instead
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
     * @deprecated use getLabel() instead
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
