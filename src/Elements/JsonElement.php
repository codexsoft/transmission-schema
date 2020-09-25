<?php


namespace CodexSoft\Transmission\Elements;


use CodexSoft\Code\Classes\Classes;
use CodexSoft\Transmission\Exceptions\InvalidJsonSchemaException;
use CodexSoft\Transmission\JsonSchemaInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * Represents JSON object
 */
class JsonElement extends AbstractElement
{

    protected ?array $acceptedTypes = ['array'];
    //public const MODE_LEAVE_EXTRA_KEYS = 1;
    //public const MODE_EXTRACT_EXTRA_KEYS = 2;
    //public const MODE_IGNORE_EXTRA_KEYS = 3;

    /** @var AbstractElement[]|string */
    protected $schema;

    /**
     * JsonElement constructor.
     *
     * @param $schema
     * @param string $label
     *
     * @throws InvalidJsonSchemaException
     */
    public function __construct($schema, string $label = '')
    {
        parent::__construct($label);
        $this->schema($schema);
    }

    protected ?string $schemaGatheredFromClass = null;

    //protected $mode = self::MODE_IGNORE_EXTRA_KEYS;
    //protected bool $ignoreExtraKeys = true;
    //protected bool $extractExtraKeysToExtraData = true;

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

        //$collection = new Constraints\Collection($constraints);
        $collection = new Constraints\Collection([
            'fields' => $constraints,
            'allowMissingFields' => false,
        ]);

        //if (!$this->isRequired) {
        //    return new Constraints\Required($result);
        //}

        //return new Constraints\Optional($result);

        return $this->isRequired ? $collection : new Constraints\Optional($collection);
        //return $this->isRequired ? new Constraints\Required($collection) : new Constraints\Optional($collection);
    }

    /**
     * @param array $data
     * @param bool $ignoreExtraKeys
     * @param bool $extractExtraKeysToExtraData
     *
     * @return array[]
     * @throws InvalidJsonSchemaException
     * @throws \CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException
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

            if (!$schemaNode instanceof AbstractElement) {
                throw new InvalidJsonSchemaException();
            }

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
     * @throws \CodexSoft\Transmission\Exceptions\ValidationDetectedViolationsException
     */
    protected function doValidate($data)
    {
        if (!\is_array($data)) {
            $this->reportViolation('Value must be JSON object, '.\gettype($data).' given');
        }

        foreach ($this->schema as $key => $value) {
            if ($this->isRequired && !\array_key_exists($key, $data)) {
                $this->reportViolation("Required key $key is missing");
            }
        }
    }

    /**
     * @param $data
     *
     * @return array
     */
    protected function doNormalizeData($data)
    {
        //if (!\is_array($data)) {
        //    throw new CouldNotNormalizeDataException();
        //}

        [$normalizedData, $extraData] = $this->normalizeDataReturningNormalizedAndExtraData($data);
        return $normalizedData;
    }

    /**
     * @param AbstractElement[]|string $schema
     *
     * @return static
     * @throws InvalidJsonSchemaException
     */
    public function schema($schema)
    {
        if (\is_string($schema)) {
            $schemaClass = $schema;
            if (!\class_exists($schemaClass) || !\in_array(JsonSchemaInterface::class, class_implements($schemaClass), true)) {
                throw new InvalidJsonSchemaException("JSON schema class $schemaClass does not implement ".JsonSchemaInterface::class);
            }
            /** @var JsonSchemaInterface $schemaClass */
            $this->schema = $schemaClass::createSchema();
            $this->schemaGatheredFromClass = $schemaClass;
        } elseif (\is_array($schema)) {
            $this->schema = $schema;
            $this->schemaGatheredFromClass = null;
        } else {
            throw new InvalidJsonSchemaException('JSON schema must be array or class implementing '.JsonSchemaInterface::class);
        }

        return $this;
    }
}
