<?php


namespace CodexSoft\Transmission\OpenApi2;


class IOSchema
{
    use SomeTrait;
    use RefTrait;

    /**
     * Can be used to decorate a user interface with information about the data produced by this
     * user interface. preferrably be short.
     */
    public ?string $title = null;

    /**
     * A description will provide explanation about the purpose of the instance described by this
     * schema.
     */
    public ?string $description = null;

    /**
     * An object instance is valid against "maxProperties" if its number of properties is less than,
     * or equal to, the value of this property.
     */
    public ?int $maxProperties = null;

    /**
     * An object instance is valid against "minProperties" if its number of properties is greater
     * than, or equal to, the value of this property.
     */
    public ?int $minProperties = null;

    /**
     * An object instance is valid against this property if its property set contains all elements
     * in this property's array value.
     * @var string[]
     */
    public array $required = [];

    /**
     * @var PropertySchema[]
     */
    public $properties;

    /**
     * A numeric instance is valid against "multipleOf" if the result of the division of the
     instance by this property's value is an integer.
     * @var number
     */
    public $multipleOf;

    /**
     * Adds support for polymorphism. The discriminator is the schema property name that is used to
     * differentiate between other schemas that inherit this schema. The property name used MUST be
     * defined at this schema and it MUST be in the required property list. When used, the value
     * MUST be the name of this schema or any schema that inherits it.
     * @var string
     */
    public $discriminator;

    /**
     * Relevant only for Schema "properties" definitions. Declares the property as "read only".
     * This means that it MAY be sent as part of a response but MUST NOT be sent as part of the
     * request. Properties marked as readOnly being true SHOULD NOT be in the required list of the
     * defined schema. Default value is false.
     */
    public bool $readOnly = false;

    /**
     * This MAY be used only on properties schemas. It has no effect on root schemas. Adds
     * Additional metadata to describe the XML representation format of this property.
     */
    public ?XmlSchema $xml = null;

    /**
     * Additional external documentation for this schema.
     */
    public ?ExternalDocumentationSchema $externalDocs = null;

    /**
     * A free-form property to include a an example of an instance for this schema.
     * @var array
     */
    public $example;

    /**
     * An instance validates successfully against this property if it validates successfully against
     * all schemas defined by this property's value.
     * @var IOSchema[]
     */
    public array $allOf = [];

    /**
     * http://json-schema.org/latest/json-schema-validation.html#anchor64
     * @var bool|object
     */
    public $additionalProperties;
}
