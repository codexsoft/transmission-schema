<?php


namespace CodexSoft\Transmission\Schema\Elements;


use CodexSoft\Transmission\Schema\Exceptions\InvalidJsonSchemaException;
use CodexSoft\Transmission\Schema\Contracts\JsonSchemaInterface;
use CodexSoft\Transmission\Schema\ValidationResult;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

/**
 * Represents JSON object
 */
class JsonElement extends AbstractElement
{
    protected ?array $acceptedPhpTypes = ['array'];
    protected bool $strictTypeCheck = true;
    protected string $openApiType = 'object';
    //public const MODE_LEAVE_EXTRA_KEYS = 1;
    //public const MODE_EXTRACT_EXTRA_KEYS = 2;
    //public const MODE_IGNORE_EXTRA_KEYS = 3;

    /** @var AbstractElement[] */
    protected array $schema;

    /**
     * JsonElement constructor.
     *
     * @param AbstractElement[]|string $schema
     * @param string $label
     *
     * @throws InvalidJsonSchemaException
     */
    public function __construct($schema, string $label = '')
    {
        parent::__construct($label);
        $this->setSchema($schema);
    }

    protected ?string $schemaGatheredFromClass = null;

    //protected $mode = self::MODE_IGNORE_EXTRA_KEYS;
    //protected bool $ignoreExtraKeys = true;
    //protected bool $extractExtraKeysToExtraData = true;

    public function toOpenApiV2ParameterArray(): array
    {
        $data = parent::toOpenApiV2ParameterArray();

        $requiredKeys = [];
        foreach ($this->schema as $key => $element) {
            if ($element->isRequired) {
                $requiredKeys[] = $key;
            }
        }
        $data['required'] = $requiredKeys;

        $properties = [];
        foreach ($this->schema as $key => $element) {
            // todo: to avoid infinite loops, $refs should be generated in some cases!
            $properties[$key] = $element->toOpenApiV2ParameterArray();
        }
        $data['properties'] = $properties;

        return $data;
    }

    /**
     * @return string[]
     */
    public function collectMentionedSchemas(): array
    {
        $mentioned = [];

        if ($this->schemaGatheredFromClass) {
            return [$this->schemaGatheredFromClass];
        }

        foreach ($this->schema as $key => $element) {
            if ($element instanceof JsonElement || $element instanceof CollectionElement) {
                \array_push($mentioned, ...$element->collectMentionedSchemas());
            }
        }

        //foreach ($this->schema as $key => $element) {
        //    if ($element instanceof JsonElement) {
        //        if ($element->getSchemaGatheredFromClass()) {
        //            $mentioned[] = $element->getSchemaGatheredFromClass();
        //        } else {
        //            \array_push($mentioned, ...$element->collectMentionedSchemas());
        //        }
        //    }
        //}

        return $mentioned;
    }

    /**
     * @return Constraint|Constraint[]
     */
    public function compileToSymfonyValidatorConstraint()
    {
        $constraints = $this->customSfConstraints;

        foreach ($this->schema as $key => $value) {
            if ($value instanceof AbstractElement) {
                $constraints[$key] = $value->compileToSymfonyValidatorConstraint();
            }
        }

        $collection = new Constraints\Collection([
            'fields' => $constraints,
            'allowMissingFields' => false,
        ]);

        return $this->isRequired ? $collection : new Constraints\Optional($collection);
    }

    /**
     * @return string|null
     */
    public function getSchemaGatheredFromClass(): ?string
    {
        return $this->schemaGatheredFromClass;
    }

    /**
     * @param array $data
     * @param bool $ignoreExtraKeys
     * @param bool $extractExtraKeysToExtraData
     *
     * @return array[]
     * @throws \CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException
     */
    public function normalizeDataReturningNormalizedAndExtraData(
        array $data,
        bool $ignoreExtraKeys = false,
        bool $extractExtraKeysToExtraData = true
    ): array
    {
        $normalizedData = [];
        $extraData = [];

        foreach ($data as $key => $value) {
            if (!\array_key_exists($key, $this->schema)) {
                if ($ignoreExtraKeys) {
                    continue;
                }

                if ($extractExtraKeysToExtraData) {
                    $extraData[$key] = $value;
                } else {
                    $normalizedData[$key] = $value;
                }

                continue;
            }

            $schemaNode = $this->schema[$key];

            //if (!$schemaNode instanceof AbstractElement) {
            //    throw new InvalidJsonSchemaException();
            //}

            $normalizedData[$key] = $schemaNode->normalizeData($data[$key]);
        }

        foreach ($this->schema as $key => $value) {
            if (\array_key_exists($key, $normalizedData)) {
                continue;
            }

            if (!$value->isRequired && $value->hasDefaultValue()) {
                $normalizedData[$key] = $value->getDefaultValue();
            }
        }

        return [$normalizedData, $extraData];
    }

    /**
     * @param $data
     *
     * @return array
     * @throws \CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException
     */
    protected function doNormalizeData($data): array
    {
        [$normalizedData, $extraData] = $this->normalizeDataReturningNormalizedAndExtraData($data);
        return $normalizedData;
    }

    /**
     * @param $data
     *
     * @return ValidationResult
     * @throws \CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException
     */
    public function getValidatedNormalizedData($data): ValidationResult
    {
        [$normalizedData, $extraData] = $this->normalizeDataReturningNormalizedAndExtraData($data);

        $sfConstraints = $this->compileToSymfonyValidatorConstraint();
        $validator = Validation::createValidator();
        $violations = $validator->validate($normalizedData, $sfConstraints);

        return new ValidationResult($normalizedData, $violations, $extraData);
    }

    /**
     * @param AbstractElement[]|string $schema
     *
     * @return static
     * @throws InvalidJsonSchemaException
     */
    protected function setSchema($schema)
    {
        if (!\is_string($schema) && !\is_array($schema)) {
            throw new InvalidJsonSchemaException('JSON schema must be array or class implementing '.JsonSchemaInterface::class);
        }

        if (\is_string($schema)) {
            $schemaClass = $schema;
            if (!\class_exists($schemaClass) || !\in_array(JsonSchemaInterface::class, class_implements($schemaClass), true)) {
                throw new InvalidJsonSchemaException("JSON schema class $schemaClass does not implement ".JsonSchemaInterface::class);
            }
            /** @var JsonSchemaInterface $schemaClass */
            $this->schema = $schemaClass::createSchema();
            $this->schemaGatheredFromClass = $schemaClass;
        }

        if (\is_array($schema)) {
            $this->schema = $schema;
            $this->schemaGatheredFromClass = null;
        }

        foreach ($this->schema as $key => $value) {
            if (!$value instanceof AbstractElement) {
                // todo: check it recursively?
                throw new InvalidJsonSchemaException('All JSON schema elements must be instances of '.AbstractElement::class.' but element with key '.$key.' does not.');
            }
        }

        return $this;
    }
}
