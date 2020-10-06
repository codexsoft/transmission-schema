<?php


namespace CodexSoft\Transmission\OpenApi2;


use CodexSoft\Transmission\OpenApi2;

trait SomeTrait
{
    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor17.
     * @var number
     */
    public $maximum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor17.
     */
    public ?bool $exclusiveMaximum = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor21.
     * @var number
     */
    public $minimum;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor21.
     */
    public ?bool $exclusiveMinimum = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor26.
     */
    public ?int $maxLength = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor29.
     */
    public ?int $minLength = null;

    /**
     * A string instance is considered valid if the regular expression matches the instance successfully.
     * See http://json-schema.org/latest/json-schema-validation.html#anchor33.
     */
    public ?string $pattern = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor42.
     */
    public ?int $maxItems = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor45.
     */
    public ?int $minItems = null;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor49.
     */
    public bool $uniqueItems = false;

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor76.
     */
    public ?array $enum = null;

    ///**
    // * A numeric instance is valid against "multipleOf" if the result of the division of the instance by this property's value is an integer.
    // * See http://json-schema.org/latest/json-schema-validation.html#anchor14.
    // * @var number
    // */
    //public $multipleOf;

    /**
     * Determines the format of the array if type array is used. Possible values are: csv - comma separated values foo,bar. ssv - space separated values foo bar. tsv - tab separated values foo\tbar. pipes - pipe separated values foo|bar. multi - corresponds to multiple parameter instances instead of multiple values for a single instance foo=bar&foo=baz. This is valid only for parameters in "query" or "formData". Default value is csv.
     */
    public string $collectionFormat = 'csv';

    /**
     * Required if type is "array". Describes the type of items in the array.
     * @var ItemsSchema
     */
    public $items;

    /**
     * The extending format for the previously mentioned type. See Data Type Formats for further details.
     */
    public ?string $format = null;

    /**
     * Schema: The type of the schema/property. The value MUST be one of "string", "number",
     * "integer", "boolean", "array" or "object".
     *
     * Parameter: The type of the parameter. Since the parameter is not located at the request body,
     * it is limited to simple types (that is, not an object). The value MUST be one of "string",
     * "number", "integer", "boolean", "array" or "file". If type is "file", the consumes MUST be
     * either "multipart/form-data" or " application/x-www-form-urlencoded" and the parameter MUST
     * be in "formData".
     */
    public string $type;

    /**
     * Sets a default value to the parameter. The type of the value depends on the defined type. See http://json-schema.org/latest/json-schema-validation.html#anchor101.
     * @var mixed
     */
    public $default = OpenApi2::UNDEFINED;
}
