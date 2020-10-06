<?php


namespace CodexSoft\Transmission\OpenApi2;


class ResponseSchema
{
    use RefTrait;

    /**
     * The key into Operations->responses array.
     *
     * @var string a HTTP Status Code or "default"
     */
    public $response;

    /**
     * A short description of the response. GFM syntax can be used for rich text representation.
     */
    public ?string $description = null;

    /**
     * A definition of the response structure. It can be a primitive, an array or an object. If this
     * field does not exist, it means no content is returned as part of the response. As an
     * \extension to the Schema Object, its root type value may also be "file". This SHOULD be
     * accompanied by a relevant produces mime-type.
     */
    public IOSchema $schema;

    /**
     * A list of headers that are sent with the response.
     * @var Header[]
     */
    public $headers;

    /**
     * An example of the response message.
     */
    public array $examples = [];

    public function __construct()
    {
        $this->schema = new IOSchema();
    }

    public function toArray(): array
    {
        return [
            "description" => $this->description,
            "schema" => [
                "type" => "string",
                "format" => "binary",
            ],
        ];
    }
}
