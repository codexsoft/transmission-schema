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
class JsonElement extends AbstractElement implements CompositeElementInterface
{
    public const MODE_EXTRACT_EXTRA_KEYS = 1;
    public const MODE_LEAVE_EXTRA_KEYS = 2;
    public const MODE_IGNORE_EXTRA_KEYS = 3;
    public const MODE_DENY_EXTRA_KEYS = 4;

    protected ?array $acceptedPhpTypes = ['array'];
    protected bool $strictTypeCheck = true;
    protected string $openApiType = 'object';

    /**
     * If extra fields are present in input data, mode define behaviour:
     * - MODE_EXTRACT_EXTRA_KEYS: these fields will be moved to extra data while normalization
     * - MODE_LEAVE_EXTRA_KEYS: these fields will stay in normalized data (without any normalization!)
     * - MODE_IGNORE_EXTRA_KEYS: these fields will be just ignored (will be just absent in normalized and extra data)
     * - MODE_DENY_EXTRA_KEYS: violation will be produced for each of them while validation
     * @var int
     */
    protected int $mode = self::MODE_EXTRACT_EXTRA_KEYS;

    /** @var AbstractElement[] */
    protected array $schema;

    protected ?string $schemaGatheredFromClass = null;

    protected ?AbstractElement $extraElementSchema = null;

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

    /**
     * @param $valuePattern
     *
     * @return static
     */
    public function extraElementSchema(AbstractElement $valuePattern): self
    {
        $this->extraElementSchema = $valuePattern;

        return $this;
    }

    protected function areExtraKeysAllowed(): bool
    {
        return $this->mode !== self::MODE_DENY_EXTRA_KEYS;
    }

    /**
     * Set mode for dealing with extra keys in input data
     * @param int $mode MUST be one of self::MODE_ constants
     *
     * @return static
     */
    public function mode(int $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Extra keys will be leaved in normalized data (but without any normalization!)
     * @return static
     */
    public function modeLeave(): self
    {
        return $this->mode(self::MODE_LEAVE_EXTRA_KEYS);
    }

    /**
     * Extra keys are not allowed and violation will be produced for each of them while validation
     * @return static
     */
    public function modeDeny(): self
    {
        return $this->mode(self::MODE_DENY_EXTRA_KEYS);
    }

    /**
     * Extra keys will be just ignored
     * @return static
     */
    public function modeIgnore(): self
    {
        return $this->mode(self::MODE_IGNORE_EXTRA_KEYS);
    }

    /**
     * todo: implement!
     *
     * @param \Closure $classToRef (string $class): string
     *
     * @return array
     */
    public function toOpenApiSchemaUsingRefs(\Closure $classToRef): array
    {
        $data = parent::toOpenApiSchema();

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
            $properties[$key] = $element->toOpenApiSchema();
        }
        $data['properties'] = $properties;

        if ($this->extraElementSchema) {
            $data['additionalProperties'] = $this->extraElementSchema->toOpenApiSchema();
        }

        return $data;
    }

    public function toOpenApiSchema(): array
    {
        $data = parent::toOpenApiSchema();

        $requiredKeys = [];
        foreach ($this->schema as $key => $element) {
            if ($element->isRequired) {
                $requiredKeys[] = $key;
            }
        }
        $data['required'] = $requiredKeys;

        if ($this->schemaGatheredFromClass) {
            $data['$ref'] = $this->createRef($this->schemaGatheredFromClass);
        } else {
            $properties = [];
            foreach ($this->schema as $key => $element) {
                /**
                 * to avoid infinite loops, $refs should be generated in some cases!
                 */
                if ($this->schemaGatheredFromClass) {
                    $properties[$key] = [
                        '$ref' => $this->createRef($this->schemaGatheredFromClass),
                    ];
                } else {
                    $properties[$key] = $element->toOpenApiSchema();
                }
            }
            $data['properties'] = $properties;
        }

        if ($this->extraElementSchema) {
            $data['additionalProperties'] = $this->extraElementSchema->toOpenApiSchema();
            //$data['additionalProperties'] = [
            //    'type' => $this->extraElementSchema->toOpenApiSchema(),
            //];
        }

        return $data;
    }

    /**
     * Recursively collect all used referenced classes that implement JsonSchemaInterface
     * @return string[]
     */
    public function collectMentionedSchemas(): array
    {
        $mentioned = [];

        if ($this->schemaGatheredFromClass) {
            return [$this->schemaGatheredFromClass];
        }

        foreach ($this->schema as $key => $element) {
            if ($element instanceof CompositeElementInterface) {
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
     * If extra fields are denied then if they are present in input data violation will occured
     * @param bool $allowExtraFields todo: remove this parameter of refactor method
     *
     * @return static
     */
    public function denyExtraFields(bool $allowExtraFields = false): self
    {
        $this->mode = self::MODE_DENY_EXTRA_KEYS;
        return $this;
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
            'allowExtraFields' => $this->areExtraKeysAllowed(),
        ]);

        return $this->isRequired ? $collection : new Constraints\Optional($collection);
    }

    /**
     * @return Constraint|Constraint[]
     */
    public function compileToFormalSymfonyValidatorConstraint()
    {
        // todo: should customSfConstraints be applied here?
        //$constraints = $this->customSfConstraints;
        $constraints = [];

        foreach ($this->schema as $key => $value) {
            if ($value instanceof AbstractElement) {
                $constraints[$key] = $value->compileToFormalSymfonyValidatorConstraint();
            }
        }

        $collection = new Constraints\Collection([
            'fields' => $constraints,
            'allowMissingFields' => false,
            'allowExtraFields' => $this->areExtraKeysAllowed(),
        ]);

        $selfConstraints = $this->generateFormalSfConstraints();
        $selfConstraints[] = new Constraints\Type(['type' => 'array']);
        $selfConstraints[] = $collection;

        //return $this->isRequired ? new Constraints\Required($selfConstraints) : new Constraints\Optional($selfConstraints);
        return $this->isRequired ? $selfConstraints : new Constraints\Optional($selfConstraints);
        //return $this->isRequired ? $collection : new Constraints\Optional($collection);
    }

    /**
     * @return string|null
     */
    public function getSchemaGatheredFromClass(): ?string
    {
        return $this->schemaGatheredFromClass;
    }

    /**
     * @return AbstractBaseElement|null
     */
    public function getExtraElementSchema(): ?AbstractBaseElement
    {
        return $this->extraElementSchema;
    }

    /**
     * @param array $data
     * @param bool $ignoreExtraKeys seems that this will be removed...
     * @param bool $extractExtraKeysToExtraData seems that this will be removed...
     *
     * @return array[]
     */
    public function normalizeDataReturningNormalizedAndExtraData(
        array $data,
        ?bool $ignoreExtraKeys = null,
        ?bool $extractExtraKeysToExtraData = null
    ): array
    {
        $normalizedData = [];
        $extraData = [];

        if ($ignoreExtraKeys === null) {
            $ignoreExtraKeys = $this->mode === self::MODE_IGNORE_EXTRA_KEYS;
        }

        if ($extractExtraKeysToExtraData === null) {
            $extractExtraKeysToExtraData = $this->mode === self::MODE_EXTRACT_EXTRA_KEYS;
        }

        foreach ($data as $key => $value) {
            /**
             * If extra keys are allowed, handling them here. If not allowed, normalization
             * will not even start because of formal validation violations.
             */
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

            if ($schemaNode instanceof JsonElement) {
                [$nodeNormalizedData, $nodeExtraData] = $schemaNode->normalizeDataReturningNormalizedAndExtraData($data[$key]);
                if ($extractExtraKeysToExtraData) {
                    $normalizedData[$key] = $nodeNormalizedData;
                    $extraData[$key] = $nodeExtraData;
                } else {
                    $normalizedData[$key] = \array_replace($nodeExtraData, $nodeNormalizedData);
                }
            } else {
                // todo: here if element is json we'll lose extra data!
                $normalizedData[$key] = $schemaNode->normalizeData($data[$key]);
            }

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
     */
    protected function doNormalizeData($data): array
    {
        [$normalizedData, $extraData] = $this->normalizeDataReturningNormalizedAndExtraData($data);
        return $normalizedData;
    }

    protected function applySubstitutes($rawData)
    {
        if (!\is_array($rawData)) {
            return $rawData;
        }

        $substitutedData = [];

        foreach ($rawData as $key => $rawValue) {
            if (\array_key_exists($key, $this->schema)) {
                $substitutedData[$key] = $this->schema[$key]->applySubstitutes($rawValue);
            } else {
                $substitutedData[$key] = $rawValue;
            }
        }

        return $substitutedData;
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
        $data = $this->applySubstitutes($data);

        /**
         * First - check that input data has acceptable types
         */
        $validator = Validation::createValidator();
        $sfFormalConstraints = $this->compileToFormalSymfonyValidatorConstraint();
        $formalViolations = $validator->validate($data, $sfFormalConstraints);
        if ($formalViolations->count()) {
            return new ValidationResult($data, $formalViolations);
        }

        if (\is_array($data)) {
            [$normalizedData, $extraData] = $this->normalizeDataReturningNormalizedAndExtraData($data);
        } else {
            [$normalizedData, $extraData] = [$data, []];
        }

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
            //if (!$value instanceof AbstractElement) {
            if (!$value instanceof AbstractBaseElement) {
                // todo: check it recursively?
                throw new InvalidJsonSchemaException('All JSON schema elements must be instances of '.AbstractElement::class.' but element with key '.$key.' does not.');
            }
        }

        return $this;
    }
}
