<?php


namespace CodexSoft\Transmission\OpenApi2;


use CodexSoft\Transmission\Elements\AbstractElement;
use CodexSoft\Transmission\OpenApi2;

class ParameterSchema implements AbstractOpenApiSchemaInterface
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * The key into Swagger->parameters or Path->parameters array.
     */
    public ?string $parameter = null;

    /**
     * The name of the parameter. Parameter names are case sensitive. If in is "path", the name field MUST correspond to the associated path segment from the path field in the Paths Object. See Path Templating for further information. For all other cases, the name corresponds to the parameter name used based on the in property.
     */
    public ?string $name = null;

    /**
     * The location of the parameter. Possible values are "query", "header", "path", "formData" or "body".
     */
    public ?string $in = null;

    /**
     * A brief description of the parameter. This could contain examples of use. GFM syntax can be used for rich text representation.
     */
    public ?string $description = null;

    /**
     * Determines whether this parameter is mandatory. If the parameter is in "path", this property is required and its value MUST be true. Otherwise, the property MAY be included and its default value is false.
     */
    public bool $required = false;

    /**
     * The schema defining the type used for the body parameter.
     * @var Schema
     */
    public $schema;

    /**
     * The type of the parameter. Since the parameter is not located at the request body, it is limited to simple types (that is, not an object). The value MUST be one of "string", "number", "integer", "boolean", "array" or "file". If type is "file", the consumes MUST be either "multipart/form-data" or " application/x-www-form-urlencoded" and the parameter MUST be in "formData".
     */
    public string $type;

    /**
     * The extending format for the previously mentioned type. See Data Type Formats for further details.
     */
    public ?string $format = null;

    /**
     * Sets the ability to pass empty-valued parameters. This is valid only for either query or formData parameters and allows you to send a parameter with a name only or an empty value. Default value is false.
     */
    public bool $allowEmptyValue = false;

    /**
     * Required if type is "array". Describes the type of items in the array.
     * @var \Swagger\Annotations\Items
     */
    public $items;

    /**
     * Determines the format of the array if type array is used. Possible values are: csv - comma separated values foo,bar. ssv - space separated values foo bar. tsv - tab separated values foo\tbar. pipes - pipe separated values foo|bar. multi - corresponds to multiple parameter instances instead of multiple values for a single instance foo=bar&foo=baz. This is valid only for parameters in "query" or "formData". Default value is csv.
     */
    public string $collectionFormat = 'csv';

    /**
     * Sets a default value to the parameter. The type of the value depends on the defined type. See http://json-schema.org/latest/json-schema-validation.html#anchor101.
     * @var mixed
     */
    public $default = OpenApi2::UNDEFINED;

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

    /**
     * See http://json-schema.org/latest/json-schema-validation.html#anchor14.
     * @var number
     */
    public $multipleOf;

    public static function createFromElement(AbstractElement $element): self
    {
        $schema = new static();
        $schema->default = $element->getDefaultValue();
        $schema->description = $element->getDescription();

        return $schema;
    }

    public function exportToArray(): array
    {
        $data = [
            'description' => $this->description,
            'type' => $this->type,
            'required' => $this->required,
        ];

        //if ($this->example !== null) {
        //    $data['example'] = $this->example;
        //}

        if ($this->default) {
            $data['default'] = $this->default;
        }

        return $data;
    }
}
