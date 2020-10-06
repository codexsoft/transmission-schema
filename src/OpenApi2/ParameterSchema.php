<?php


namespace CodexSoft\Transmission\OpenApi2;


use CodexSoft\Transmission\Elements\AbstractElement;

/**
 * Describes a single operation parameter.
 *
 * A Swagger "Parameter Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#parameterObject
 */
class ParameterSchema implements AbstractOpenApiSchemaInterface
{
    use SomeTrait;
    use RefTrait;

    /**
     * The key into Swagger->parameters or Path->parameters array.
     */
    public ?string $parameter = null;

    /**
     * The name of the parameter. Parameter names are case sensitive. If in is "path", the name
     * field MUST correspond to the associated path segment from the path field in the Paths Object.
     * See Path Templating for further information. For all other cases, the name corresponds to the
     * parameter name used based on the in property.
     */
    public ?string $name = null;

    /**
     * The location of the parameter. Possible values are "query", "header", "path", "formData" or
     * "body".
     */
    public ?string $in = null;

    /**
     * A brief description of the parameter. This could contain examples of use. GFM syntax can be
     * used for rich text representation.
     */
    public ?string $description = null;

    /**
     * Determines whether this parameter is mandatory. If the parameter is in "path", this property
     * is required and its value MUST be true. Otherwise, the property MAY be included and its
     * default value is false.
     */
    public bool $required = false;

    /**
     * The schema defining the type used for the body parameter.
     */
    public IOSchema $schema;

    /**
     * Sets the ability to pass empty-valued parameters. This is valid only for either query or
     * formData parameters and allows you to send a parameter with a name only or an empty value.
     * Default value is false.
     */
    public bool $allowEmptyValue = false;

    public function __construct()
    {
        $this->schema = new IOSchema();
    }

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
